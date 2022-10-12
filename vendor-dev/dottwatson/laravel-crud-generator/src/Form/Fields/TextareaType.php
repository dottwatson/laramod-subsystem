<?php

namespace Dottwatson\CrudGenerator\Form\Fields;


class TextareaType extends FormField
{

    /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'textarea';
    }
}
