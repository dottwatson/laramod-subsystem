<?php
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;


class Phone extends Field {

    protected $fixedType = 'phone';

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['prepend'] = '<i class="fa fa-phone"></i>';

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
