<?php
namespace Dottwatson\DatatableGenerator;


use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class DatatableGeneratorServiceProvider extends BaseServiceProvider{

    /**
     * Bootstrap the package's services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-datatable-generator');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-datatable-generator'),
        ],'datatable-generator-views');


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