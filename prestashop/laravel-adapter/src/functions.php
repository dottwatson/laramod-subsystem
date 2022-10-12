<?php 
if(!function_exists('context')){
    /**
     * get Context
     *
     * @return Context
     */
    function context(){
        static $processed = false;
        
        if ( !app()->runningInConsole() ){
            if(!$processed){
                if(defined('_PS_ADMIN_DIR_')){
                    $controller = new AdminController;
                    // $controller->init();
                }
                else{
                    $controller = new FrontController;
                    $controller->init();
                }
            
                $processed = true;
            }
        }

        return Context::getContext();
    }
}

if(!function_exists('employee')){
    
    /**
     * get Employee
     *
     * @return Employee|null
     */
    function employee(){
        return context()->employee;
    }
}

if(!function_exists('customer')){
    
    /**
     * get customer
     *
     * @return Customer|null
     */
    function customer(){
        return context()->customer;
    }
}

/**
 * check if request comes from admin 
 *
 * @return bool
 */
function ps_is_admin()
{
    return config('prestashop.isAdminController') == true;
}

/**
 * returns array of parameters for build a prestashop url
 *
 * @param string|null $controllerAndRoute Syntax PrestashopControllerName::laravelRoute 
 * @param array $parameters
 * @return array [controller,laravelRoute,queryParameters]
 */
function build_ps_link(string $controllerAndRoute,array $parameters = [])
{
    $blocks = explode('::',$controllerAndRoute,2);

    //only controllername. Route became /
    if(!isset($blocks[1])){
        return build_ps_link("{$blocks[0]}::/",$parameters);
    }
    
    list($prestaShopControllerName,$laravelRoute) = explode('::',$controllerAndRoute,2);

    $prestaShopControllerName   = trim($prestaShopControllerName);
    $laravelRoute               = trim($laravelRoute);
    
    if($prestaShopControllerName == ''){
        $prestaShopControllerName = config('prestashop.controllerName');
    }

    $laravelRouteInfo = parse_url($laravelRoute);

    //Using the route() function on laravel, may generate a full url with query parameters
    //so I need to clear the base url and query string to have a clean route
    //preserving query parameters to merge them into passed parameters.

    //if the laravelRoute contains query string, remove it and append them into parameters argument
    if(isset($laravelRouteInfo['query'])){
        $laravelRoute   = str_replace("?{$laravelRouteInfo['query']}",'',$laravelRoute);
        parse_str($laravelRouteInfo['query'],$queryArray);
        $parameters     = array_merge($queryArray,$parameters);
    }

    //if schema is present, remove the base url of application, to kkeep only the requested url
    if(isset($laravelRouteInfo['scheme'])){
        $laravelRoute = str_replace(base_url(),'',$laravelRoute);
    }
    
    return ['controller'=>$prestaShopControllerName,'route'=>$laravelRoute,'query'=>$parameters];
}

/**
 * get admin link to controller and laravel route
 *
 * @param string|null $controllerAndRoute Syntax PrestashopControllerName::laravelRoute 
 * @param array $parameters
 * @return string
 */
function ps_admin_url(string $controllerAndRoute,array $parameters = [])
{
    $data = build_ps_link($controllerAndRoute,$parameters);

    $parameters[config('prestashop.routeName')] = $data['route'];

    return context()->link->getAdminLink($data['controller'],true,[],$parameters);
}

/**
 * get admin link to controller and laravel route
 *
 * @param string|null $controllerAndRoute Syntax PrestashopControllerName::laravelRoute 
 * @param array $parameters
 * @return string
 */
function ps_url(string $controllerAndRoute,array $parameters = [])
{
    $data = build_ps_link($controllerAndRoute,$parameters);

    $parameters[config('prestashop.routeName')] = $data['route'];

    return context()->link->getModuleLink('laramod',$data['controller'],$parameters);
}

/**
 * build a prestashoip admin url using laravel route names
 *
 * @param string $controllerAndRoute controller::route
 * @param array $parameters
 * @return string
 */
function ps_admin_route(string $controllerAndRoute,array $parameters = [])
{
    
    $data = build_ps_link($controllerAndRoute,$parameters);


    if($data['route'] != '/' && $data['route'] != ''){
        $data['route'] = ltrim(route($data['route'],$parameters,false),'/');
    }

    if(strpos($data['route'],'?') === false){
        $parameters = [];
    }
    else{
        list($routePath,$queryStr) = explode('?',$data['route'],2);
        $data['route'] = $routePath;
        parse_str($queryStr,$parameters);
    }

    $parameters[config('prestashop.routeName')] = $data['route'];

    return context()->link->getAdminLink($data['controller'],true,[],$parameters);
}

/**
 * build a prestashoip url using laravel route names
 *
 * @param string $controllerAndRoute controller::route
 * @param array $parameters
 * @return string
 */
function ps_route(string $controllerAndRoute,array $parameters = [])
{
    
    $data = build_ps_link($controllerAndRoute,$parameters);


    if($data['route'] != '/' && $data['route'] != ''){
        $data['route'] = ltrim(route($data['route'],$parameters,false),'/');
    }

    if(strpos($data['route'],'?') === false){
        $parameters = [];
    }
    else{
        list($routePath,$queryStr) = explode('?',$data['route'],2);
        $data['route'] = $routePath;
        parse_str($queryStr,$parameters);
    }

    $parameters[config('prestashop.routeName')] = $data['route'];

    return context()->link->getModuleLink('laramod',$data['controller'],$parameters);
}
