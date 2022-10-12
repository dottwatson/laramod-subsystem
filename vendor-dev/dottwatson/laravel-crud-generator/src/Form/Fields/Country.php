<?php 
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;
use App\Models\Country as CountryModel;


class Country extends Field {

    protected function getTemplate()
    {
        // At first it tries to load config variable,
        // and if fails falls back to loading view
        // resources/views/fields/datetime.blade.php
        return 'laravel-crud-generator::form.fields.country';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['choices'] = [];

        $countries = CountryModel::all();
        foreach($countries as $country){
            $options['choices'][$country->id] = "{$country->name} ({$country->iso_3166_1_alpha_3})";
        }

        $options['empty_value'] = '';
        $options['selected'] = '';

        $this->options['attr'] = (isset($this->options['attr']))?$this->options['attr']:[];
        $this->options['attr']['class'] = (isset($this->options['attr']['class']))
            ?$this->options['attr']['class']:'';
        
        $this->options['attr']['class'].=' select2';

        $this->options['attr']['style'] = (isset($this->options['attr']['style']))
            ?$this->options['attr']['style'].';':'';
        $this->options['attr']['style'].='width:300px;';

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
