<?php
namespace Dottwatson\CrudGenerator\Crud\Forms;

use Dottwatson\CrudGenerator\Form\Form;
use Dottwatson\CrudGenerator\Models\RelationalExtrafieldsModel;

class RelationalExtraFieldsForm extends Form
{
   
    protected $referenceId;
    protected $referenceName;


    // protected function getTemplate()
    // {
    //     return 'laravel-crud-generator::form.relational-extrafields-form';
    // }
    
    public function fillData(array $data = [])
    {
        $model = new RelationalExtrafieldsModel;
        foreach($data as $k=>$v){
            $model->{$k}= $v;
        }

        $this->setModel($model);

        return $this;
    }

    // public function setModel($data = null)
    // {
    //     $data = (is_null($data))?[]:$data;
    //     $data = (!is_array($data))?[$data]:$data;

    //     $model = new RelationalExtrafieldsModel;
    //     foreach($data as $k=>$v){
    //         $model->{$k}= $v;
    //     }
    //     return parent::setModel($model);
    // }

    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;
    
        return $this;
    }
    
    
    public function setReferenceName($referenceName)
    {
        $this->referenceName = $referenceName;
    
        return $this;
    }

    public function buildForm()
    {
        return [];
    }


    public function render($options, $fields, $showStart, $showFields, $showEnd)
    {
        foreach($fields as $fieldName=>$field){
            $newName = "relational[{$this->referenceName}][extrafields][{$this->referenceId}][{$fieldName}]";
            // $newName = "relational[{$name}][extrafields][{$item['id']}][{$extraName}]";
            $field->setName($newName);
        }

        return parent::render($options,$fields,false,true,false);
    }

}
