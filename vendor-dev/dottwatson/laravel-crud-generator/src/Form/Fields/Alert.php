<?php 
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;


class Alert extends Field {

    protected function getTemplate()
    {
        // At first it tries to load config variable,
        // and if fails falls back to loading view
        // resources/views/fields/datetime.blade.php
        return 'laravel-crud-generator::form.fields.alert';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['title']   = $this->options['title']   ?? '';
        $options['type']    = $this->options['type']    ?? 'info';
        $options['message'] = $this->options['message'] ?? '';
        
        return parent::render($options, $showLabel, $showField, $showError);
    }
}
