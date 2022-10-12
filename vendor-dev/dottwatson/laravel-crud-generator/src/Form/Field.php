<?php 
namespace Dottwatson\CrudGenerator\Form;

use Illuminate\Support\Facades\Config;
use Dottwatson\CrudGenerator\Form\Fields\FormField;


class Field extends FormField {

    protected $fixedType='';

    protected function getRenderData()
    {
        $viewPath = Config::get('view.paths');

        return [
            'viewPath'=>$viewPath[0],
            'formBuilderViewPath'=>$viewPath[0].'/vendor/laravel-form-builder/'
        ];
    }

    protected function getTemplate()
    {
        // At first it tries to load config variable,
        // and if fails falls back to loading view
        // resources/views/fields/datetime.blade.php
        return 'laravel-crud-generator::form.fields.generic';
    }


    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        if($this->fixedType != ''){
            $options['type'] = $this->fixedType;
        }

        return parent::render($options, $showLabel, $showField, $showError);
    }
}


