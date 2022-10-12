<?php

namespace  Dottwatson\CrudGenerator\Form\Fields;


class Select2Type extends FormField
{

    /**
     * The name of the property that holds the value.
     *
     * @var string
     */
    protected $valueProperty = 'selected';

     /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'laravel-crud-generator::form.fields.select2';
    }

    /**
     * @inheritdoc
     */
    public function getDefaults()
    {
        return [
            'choices' => [],
            'option_attributes' => [],
            'empty_value' => null,
            'selected' => null
        ];
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $this->options['attr'] = (isset($this->options['attr']))?$this->options['attr']:[];
        $this->options['attr']['class'] = (isset($this->options['attr']['class']))
            ?$this->options['attr']['class']:'';
        
        $this->options['attr']['class'].=' select2';
        
        return parent::render($options, $showLabel, $showField, $showError);
    }

}
