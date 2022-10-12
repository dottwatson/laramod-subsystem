<?php

class LaravelAdapterAutoloader{

    /**
     * here define overrides for both laravel and prestashop
     * It helps you to make systems compatible because each uss symfony or parts of it
     *
     * @var array
     */
    protected static $overrides = [
        'PrestaShopBundle\Event\Dispatcher\NullDispatcher' => 'PrestaShopBundle/Event/Dispatcher/NullDispatcher.php'
    ];


    /**
     * here define the classes that will be loaded excluvely from laravel, when availables in both vendors 
     *
     * @var array
     */
    protected static $laravelSpecificClass = [
    ];

    /**
     * here define the namespaces that will be loaded excluvely from laravel, when availables in both vendors 
     *
     * @var array
     */
    protected static $laravelSpecificNamespace  = [];

    /**
     * here define the classes that will be loaded excluvely from prestashop, when availables in both vendors 
     *
     * @var array
     */
    protected static $psSpecificClass = [
        // 'Symfony\\Component\\HttpKernel\\Config\\FileLocator',
        // 'Symfony\\Component\\HttpKernel\\Log\\DebugLoggerInterface',
        'Symfony\Component\\Routing\\Loader\\YamlFileLoader'
    ];

    /**
     * here define the namespaces that will be loaded excluvely from prestashop, when availables in both vendors 
     *
     * @var array
     */
    protected static $PsSpecificNamespace   = [
        'GuzzleHttp'
    ];


    /**
     * The laoded classes list
     *
     * @var array
     */
    protected static $loadedClasses = [];

    /**
     * The psr4 classlist defined for prestashop and loaded from ps composer
     *
     * @var null|array
     */
    protected static $psAutoloader;

    /**
     * The psr4 classlist defined for laravel and loaded from laravel composer
     *
     * @var null|array
     */
    protected static $laravelAutoloader;


    /**
     * loads the class, according to constrictions
     *
     * @param string $class
     * @return void
     */
    public static function load($class)
    {
        // global $kernel;
        // var_dump($kernel);die();
        
        static::prepare();
        $class      = trim($class);
        $classInfo  = static::parseRequestedClass($class);

        //OVERRIDES
        if(static::isClassOf($class,static::$overrides)){
            $target = realpath(__DIR__.'/Override/'.static::$overrides[$class]);
            static::log(['isOverride',$class,$target]);
            static::autoloadFile($target,$class);
        }
        //LARAVEL
        if(static::isClassReservedIn($class,static::$laravelSpecificClass)){
            static::log(['isClassReservedIn','laravel',$class]);
            $target = static::isClassOf($class,static::$laravelAutoloader)
                ?static::$laravelAutoloader[$class]
                :null;
            
            static::autoloadFile($target,$class);
        }
        if(static::isNamespaceReservedIn($classInfo['namespaces'],static::$laravelSpecificNamespace))
        {
            static::log(['isNamespaceReservedIn','laravel',$classInfo['namespaces']]);
            $target = static::isClassOf($class,static::$laravelAutoloader)
                ?static::$laravelAutoloader[$class]
                :null;

            static::autoloadFile($target,$class);
        }
        //PRESTASHOP
        if(static::isClassReservedIn($class,static::$psSpecificClass)){
            static::log(['isClassReservedIn','prestashop',$class]);
            $target = static::isClassOf($class,static::$psAutoloader)
                ?static::$psAutoloader[$class]
                :null;
            
            static::autoloadFile($target,$class);
        }
        if(static::isNamespaceReservedIn($classInfo['namespaces'],static::$PsSpecificNamespace))
        {
            static::log(['isNamespaceReservedIn','PRESTASHOP',$classInfo['namespaces']]);
            $target = static::isClassOf($class,static::$psAutoloader)
                ?static::$psAutoloader[$class]
                :null;
            
            static::autoloadFile($target,$class);
        }
        if(static::isClassOf($class,static::$laravelAutoloader)){
            $target = static::$laravelAutoloader[$class];
            static::autoloadFile($target,$class);
        }
        if(static::isClassOf($class,static::$psAutoloader)){
            $target =  static::$psAutoloader[$class];
            static::autoloadFile($target,$class);
        }


        return;
    }

    /**
     * clean the logs loaded classes
     *
     * @return null
     */
    protected static function logClean()
    {
        if(env('PRESTASHOP_LOADER_DEBUGGER',false) == true){
            $file   = storage_path('logs/prestashop/autoload.log');
            @unlink($file);
        }
    }

    /**
     * logs classes
     *
     * @param mixed $msg
     * @return void
     */
    protected static function log($msg)
    {
        if(env('PRESTASHOP_LOADER_DEBUGGER',false) == true){
            if(!is_string($msg)){
            $msg = json_encode($msg);
            }

            $file   = storage_path('logs/prestashop/autoload.log');
            $msg    = "[".date('Y-m-d H:i:s')."] autoloader.INFO: {$msg}";
            
            if(!is_dir(dirname($file))){
                mkdir(dirname($file),0755,true);
            }
            
            file_put_contents($file,$msg."\n",FILE_APPEND|LOCK_EX);
        }
    }

    /**
     * Effectively load the file
     *
     * @param string|null $filePath
     * @param string $class
     * @return void
     */
    public static function autoloadFile(string $filePath = null,$class)
    {
        static::log("{$class} => {$filePath}");

        if(!isset(static::$loadedClasses[$class]) && $filePath != ''){
            static::$loadedClasses[$class] = $filePath;
            include($filePath);
        }
    }

    /**
     * Check if namespace is reserved in list
     *
     * @param array $namespaceList
     * @param array $reservedList
     * @return boolean
     */
    protected static function isNamespaceReservedIn(array $namespaceList,array $reservedList)
    {
        foreach($namespaceList as $namespace){
            if(in_array($namespace,$reservedList)){
                return true;
            }
        }

        return false;
    }

    protected static function isClassReservedIn(string $class,array $reservedList)
    {
        return  in_array($class,$reservedList);
    }

    /**
     * check if class exists in list
     *
     * @param string $class
     * @param array $classList
     * @return boolean
     */
    protected static function isClassOf(string $class,array $classList)
    {
        return isset($classList[$class]);
    }

    /**
     * parse a class name and returns all namespaces tree and class name
     *
     * @param string $class
     * @return array
     */
    protected static function parseRequestedClass(string $class){
        $result = ['namespaces' => [],'name'=>null];

        $requestedClass     = trim($class,'\\');
        $blocks             = explode('\\',$requestedClass);
        $result['name']     = array_pop($blocks);
        if(count($blocks)){
            $result['namespaces'][] = implode('\\',$blocks);

            while(count($blocks) > 0){
                array_pop($blocks);
                if($blocks){
                    $result['namespaces'][] = implode('\\',$blocks);
                }
            }
        }

        return $result;
    }


    protected static function prepare()
    {
        if(is_null(static::$psAutoloader) || is_null(static::$laravelAutoloader)){
            static::logClean();
            static::$psAutoloader       = include _PSL_BASE_DIR_.'/vendor/composer/autoload_classmap.php';
            static::$laravelAutoloader  = include base_path().'/vendor/composer/autoload_classmap.php';
        }

    } 

}

spl_autoload_register('LaravelAdapterAutoloader::load',true,true);

// spl_autoload_register(function ($class) {
//     static $psAutoloader;
//     static $laravelAutoloader;

//     if(is_null($psAutoloader) || is_null($laravelAutoloader)){
//         $psAutoloader       = include _PSL_BASE_DIR_.'/vendor/composer/autoload_classmap.php';
//         $laravelAutoloader  = include base_path().'/vendor/composer/autoload_classmap.php';
//     }

//     $forcePrestashop = [
//         'GuzzleHttp\\Client'
//     ];

//     if(in_array($class,$forcePrestashop)){
//         include_once($psAutoloader[$class]);
//     }
//     elseif(isset($laravelAutoloader[$class])){
//         echo($class);die();
//         include_once($laravelAutoloader[$class]);
//     }
//     elseif(isset($psAutoloader[$class])){
//         include_once($psAutoloader[$class]);
//     }
//     else{
//         return;
//     }
// },true,true);
