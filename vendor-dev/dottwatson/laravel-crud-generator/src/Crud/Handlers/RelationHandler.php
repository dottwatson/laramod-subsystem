<?php 
namespace Dottwatson\CrudGenerator\Crud\Handlers;

use Illuminate\Support\Arr;

/**
 * This is the relational handler used to manage relational crud informations
 */
class RelationHandler{

    protected $booted        = false;
    protected $userConfig    = [];
    protected $fullConfig    = [];
    protected $defaultConfig = [
        'crud'      => [
            'field' => null, //the form field
            'sheet' => null, //the sheet field
        ],
        'item'=>[
            'url'   => null , // called when add a item from selection
            'route' => null, //when route is empty
            'view'  => null, //the view used to rendere the item
            'crud'  => [
                'form'  => null, //extrafields form (empty or a form object)
                'sheet' => null, // empty or a sheet object
            ]
        ]
    ];


    public function __construct()
    {
        $this->init();

        $this->makeConfig();
        
        
    }

    /**
     * initialize the object, used also tu set the configuration
     *
     * @return void
     */
    public function init()
    {
        //put here your logic

        // here also will be set the configuration from user or extended class
        // if not called will be fired itself only on the first class call
        // for set the configuration is useful use
        // $this->setUserConfig(myArrayOfParameters);
    }

    /**
     * initialize the object, composing the fullConfig
     *
     * @return void
     */
    protected function makeConfig()
    {
        $this->fullConfig = array_replace_recursive(
            $this->defaultConfig,
            $this->userConfig
        );
    }

    /**
     * get informations from config
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(string $key = null,$default = null)
    {
        if($key === null){
            return $this->fullConfig;
        }
        else{
            $value = Arr::get($this->fullConfig,$key,$default);
            return ($value === null)?$default:$value;
        }
    }

    /**
     * set configuration 
     *
     * @param array $config
     * @return void
     */
    public function setUserConfig(array $config = [])
    {
        $this->userConfig = $config;
        $this->makeConfig();

        return $this;
    }


    /**
     * returns the primitive configuration imputed by user definition
     *
     * @return array
     */
    public function getUserConfig()
    {
        return $this->userConfig;
    }

    /**
     * retuns the default configuration
     *
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->defaultConfig;
    }
}