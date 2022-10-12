<?php
namespace Dottwatson\CrudGenerator\Form;

use Illuminate\Support\Facades\App;


trait FormBuilderTrait
{

    /**
     * Create a Form instance.
     *
     * @param string $name Full class name of the form class.
     * @param array  $options Options to pass to the form.
     * @param array  $data additional data to pass to the form.
     *
     * @return \Dottwatson\CrudGenerator\Form\Form
     */
    protected function form($name, array $options = [], array $data = [])
    {
        return App::make('laravel-form-builder')->create($name, $options, $data);
    }

    /**
     * Create a plain Form instance.
     *
     * @param array $options Options to pass to the form.
     * @param array $data additional data to pass to the form.
     *
     * @return \App\Forms\Form
     */
    protected function plain(array $options = [], array $data = [])
    {
        return App::make('laravel-form-builder')->plain($options, $data);
    }
}