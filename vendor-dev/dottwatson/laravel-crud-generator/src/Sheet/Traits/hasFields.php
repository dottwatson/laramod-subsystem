<?php
namespace Dottwatson\CrudGenerator\Sheet\Traits;

use Dottwatson\CrudGenerator\Sheet\Sheet;

trait hasFields{
    protected $fields = [];


    public function addField(string $name,string $type,array $options = [])
    {
        $sheet = (is_a($this,Sheet::class))
            ?$this
            :$this->getSheet();


        $this->fields[$name] = $sheet->buildField($name,$type,$options);

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