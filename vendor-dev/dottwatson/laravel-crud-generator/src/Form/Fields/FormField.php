<?php

namespace  Dottwatson\CrudGenerator\Form\Fields;

use Kris\LaravelFormBuilder\Fields\FormField as LaravelFormBuilderFieldsFormFiels; 

/**
 * Class FormField
 *
 * @package Dottwatson\CrudGenerator\Form\Fields
 */
abstract class FormField extends LaravelFormBuilderFieldsFormFiels
{

    /**
     * Render the field.
     *
     * @param array $options
     * @param bool  $showLabel
     * @param bool  $showField
     * @param bool  $showError
     * @return string
     */
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $this->prepareOptions($options);
        $value = $this->getValue();
        $defaultValue = $this->getDefaultValue();

        if ($showField) {
            $this->rendered = true;
        }

        // Override default value with value
        if (!$this->isValidValue($value) && $this->isValidValue($defaultValue)) {
            $this->setOption($this->valueProperty, $defaultValue);
        }

        if (!$this->needsLabel()) {
            $showLabel = false;
        }

        if ($showError) {
            $showError = $this->parent->haveErrorsEnabled();
        }

        $data = $this->getRenderData();

        return $this->formHelper->getView()->make(
            $this->getViewTemplate(),
            $data + [
                'form' => $this->getParent(),
                'name' => $this->name,
                'nameKey' => $this->getNameKey(),
                'type' => $this->type,
                'options' => $this->options,
                'showLabel' => $showLabel,
                'showField' => $showField,
                'showError' => $showError,
                'errorBag'  => $this->parent->getErrorBag(),
                'translationTemplate' => $this->parent->getTranslationTemplate(),
            ]
        )->render();
    }

}
