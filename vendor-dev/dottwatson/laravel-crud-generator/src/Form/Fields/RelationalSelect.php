<?php 
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;


class RelationalSelect extends Relational {

    protected $fixedType = 'relational-table';

    protected function getTemplate()
    {
        return 'laravel-crud-generator::form.fields.relational-select';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        return parent::render($options, $showLabel, $showField, $showError);
    }
}
