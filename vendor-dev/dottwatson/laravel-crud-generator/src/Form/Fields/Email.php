<?php
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;


class Email extends Field {

    protected $fixedType = 'email';

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['prepend'] = '<i class="fa fa-envelope"></i>';

        return parent::render($options, $showLabel, $showField, $showError);
    }


}
