<?php
namespace Dottwatson\CrudGenerator\Form\Traits;

use Dottwatson\CrudGenerator\Form\Form;

trait hasFields{
    protected $fields = [];


    public function addField(string $name,string $type,array $options = [])
    {
        $form = $this->getForm();


        $this->fields[$name] = $form->buildField($name,$type,$options);

        return $this;
    }

    public function addFields(array $fields = [])
    {
        foreach($fields as $field){
            $this->addField(...$field);
        }
    
        return $this;
    }

    public function getField(string $name)
    {
        return $this->fields[$name] ?? null;
    }

    public function removeField(string $name)
    {
        unset($this->fields[$name]);

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }
}