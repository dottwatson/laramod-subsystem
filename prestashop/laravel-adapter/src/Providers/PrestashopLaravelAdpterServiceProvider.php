<?php
namespace PrestashopLaravelAdpter\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class PrestashopLaravelAdpterServiceProvider extends ServiceProvider{


    public static function disableOnComposerEvents()
    {
        $marker = __DIR__.'/../locker.composer';
        if(is_file($marker) && (time() - filemtime($marker)) > 15){
            @unlink($marker);
        }
        
        touch($marker);
    }


    public  function register()
    {

    }


    public function boot()
    {
        $this->checkCorrections();

        define('_PSL_BASE_DIR_',app_path('/../../../../'));
        define('_PSL_CONFIG_DIR_',_PSL_BASE_DIR_.'/config');
        
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->requestRegisterMacro();

        $loadPrestashop = false;
        $marker         = __DIR__.'/../locker.composer';

        if(!is_file($marker)){
            $loadPrestashop = true;
        }
        elseif(is_file($marker) && (time() - filemtime($marker)) > 15){
            @unlink($marker);
            $loadPrestashop = true;
        }

        if($loadPrestashop){
            include_once __DIR__.'/../LaravelAdapterAutoloader.php';
    
            $request = request();
        
            $asAdminRequest = $request->header('ps-controller-type') == 'backend';
    
            //if the ps_admin_dir passed with cookies is filled, we are in the backend context,
            //so the Employe is correctly set and other stuff
            if($asAdminRequest){
                define('_PS_ADMIN_DIR_',$request->header('ps-admin-dir'));
            }
    
            $currentControllerName = $request->input('controller');
            
            //this is the origina /config/config.inc.php on prestashop
            //only is removed the vendor autoload. spl do it for you
            include_once __DIR__.'/../adapterLoader.php';
    
            //ereditate the main variables used to interact with prestashop 
            $context = context();        
            
            config([
                'app.locale'    => $context->language->iso_code,
                'app.name'      => $context->shop->name,
                'app.url'       => $context->shop->getBaseURL(),
    
                //store some information about current request
                'prestashop.isPrestashop'      => $request->isPrestashop(),
                'prestashop.isAdminController' => $asAdminRequest,
                'prestashop.controllerName'    => $currentControllerName,
                'prestashop.requestQuery'      => $request->all(),
                'prestashop.routeName'         => $request->header('ps-route-name','')
            ]);
        }           
        
        $this->loadCommands();
    }

    /**
     * Prestashop main classes will be in conflict with laravel aliases.
     * This will check them and advise you to disable the aliases
     *
     * @return void
     */
    protected function checkCorrections()
    {
        $laravelAliases     = config('app.aliases');
        $laravelCorrections = ['Cookie','Cache'];

        foreach($laravelCorrections as $k=>$classAliasToCorrect){
            if(!isset($laravelAliases[$classAliasToCorrect])){
                unset($laravelCorrections[$k]);
            }
        }

        if($laravelCorrections){
            throw new \Exception('Comment into config/app.php:aliases the alias related to '.implode(",",$laravelCorrections));
        }
    }

    protected function requestRegisterMacro()
    {
        Request::macro('isPrestashop',function(){
            $request = request();
            return ($request->header('ps-controller-type') !== null && $request->header('ps-route-name') !== null);
        });
    }


    protected function loadCommands()
    {
        $this->commands([
            \PrestashopLaravelAdpter\Commands\CreateHookCommand::class
        ]);
    }

}