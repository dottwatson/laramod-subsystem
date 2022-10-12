<?php
namespace Dottwatson\CrudGenerator;

use Dottwatson\CrudGenerator\Http\Controllers\AttachmentController;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Route;

class CrudGeneratorServiceProvider extends BaseServiceProvider{

    /**
     * Bootstrap the package's services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-crud-generator');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-crud-generator'),
        ],'laravel-crud-generator-views');

        $this->publishes([
            __DIR__.'/../resources/assets/styles.css' => public_path('vendor/crud-generator/styles.css'),
            __DIR__.'/../resources/assets/scripts.js' => public_path('vendor/crud-generator/scripts.js'),
        ],'laravel-crud-generator-assets');

        
        Route::macro('registerAttachments',function(string $identifier=null,string $controllerName = null){
            $basePath = (!$identifier)
                ?'attachment'
                :"attachments/{$identifier}";

            $baseName = (!$identifier)
                ?'attachments'
                :"{$identifier}.attachments";

            $controllerName = ($controllerName)
                ?$controllerName
                :\Dottwatson\CrudGenerator\Http\Controllers\AttachmentController::class;


            Route::post("{$basePath}/upload",       [$controllerName, 'upload'])->name("{$baseName}");
            Route::get("{$basePath}/{id}/download", [$controllerName, 'download'])->name("{$baseName}.download");
            Route::get("{$basePath}/{id}/view",     [$controllerName, 'view'])->name("{$baseName}.view");
        });

        Route::macro('registerRelations',function(string $identifier=null,string $controllerName = null){
            $basePath = (!$identifier)
                ?'relations'
                :'relations/'.str_replace('.','/',$identifier);

            $baseName = (!$identifier)
                ?'relations'
                :"{$identifier}.relations";

            $controllerName = ($controllerName)
                ?$controllerName
                :\Dottwatson\CrudGenerator\Http\Controllers\RelationalController::class;


            Route::post("{$basePath}/item",[$controllerName, 'item'])->name("{$baseName}.item");
            Route::post("{$basePath}/items",[$controllerName, 'items'])->name("{$baseName}.items");
        });


        Route::macro('crud',function(string $singularTarget,string $plularTarget,string $controller){
            Route::get($plularTarget,[$controller,'index'])->name($singularTarget.'.index');
            Route::get($singularTarget.'/add',[$controller,'add'])->name($singularTarget.'.add');
            Route::get($singularTarget.'/{id}/sheet',[$controller,'sheet'])->name($singularTarget.'.sheet');
            Route::get($singularTarget.'/{id}/edit',[$controller,'edit'])->name($singularTarget.'.edit');

            Route::post($plularTarget.'/data',[$controller,'data'])->name($singularTarget.'.data');
            Route::post($singularTarget.'/store',[$controller,'store'])->name($singularTarget.'.store');
            Route::post($singularTarget.'/{id}/update',[$controller,'update'])->name($singularTarget.'.update');
            Route::post($singularTarget.'/{id}/delete',[$controller,'delete'])->name($singularTarget.'.delete');
        });



    }

    /**
     * Get the absolute path to some package resource.
     *
     * @param  string  $path  The relative path to the resource
     * @return string
     */
    private function packagePath($path)
    {
        return __DIR__."/$path";
    }

}