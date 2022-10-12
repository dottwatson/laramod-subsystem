<?php 
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;


class Relational extends Field {

    protected $fixedType = 'relational';

    protected function getTemplate()
    {
        return 'laravel-crud-generator::form.fields.relational';
    }


    //$name, $type, Form $parent, array $options = []


    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $form   = $this->getParent();
        $model  = $form->getModel();


        $config         = $model->getRelationConfig($this->name);

        if(is_array($config['handler'])){
            $handler = new \Dottwatson\CrudGenerator\Crud\Handlers\RelationHandler;
            $handler->setUSerConfig($config['handler']);
        }
        elseif(is_object($config['handler'])){
            $handler = $config['handler'];
            $handler->init();
        }
        else{
            $handlerClass   = $config['handler'];
            $handler        = new $handlerClass;
            $handler->init();
        }


        $formFieldClass = $handler->getConfig('crud.field');
        
        $fullConfig   = [
            'through' => (isset($config['through']) && $config['through'])? $config['through']:$this->name,
            'handler' => $config['handler']
            ] + $handler->getConfig();
        $field        = new $formFieldClass($this->name,'',$form,$this->options);
        $options      = [
            'model_name'    => get_class($model),
            'model_id'      => $model->getKey(),
            'config'        => $fullConfig,
        ];
        
        

        return $field->render($options, $showLabel, $showField , $showError);
    }
}
