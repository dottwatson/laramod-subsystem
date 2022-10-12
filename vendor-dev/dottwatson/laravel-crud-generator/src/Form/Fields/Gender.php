<?php 
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;
use App\Models\Country as CountryModel;


class Gender extends Field {

    protected function getTemplate()
    {
        // At first it tries to load config variable,
        // and if fails falls back to loading view
        // resources/views/fields/datetime.blade.php
        return 'laravel-crud-generator::form.fields.gender';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['choices'] = config('common.genders');
        $options['empty_value'] = '';
        $options['selected'] = '';

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
