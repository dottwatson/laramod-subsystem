<?php
namespace Dottwatson\CrudGenerator\Crud\Forms;

use Dottwatson\CrudGenerator\Form\Form;

class AttachmentExtraFieldsForm extends Form
{
   
    protected function getTemplate()
    {
        return 'laravel-crud-generator::form.attachment-extrafields-form';
    }
    
    public function buildForm()
    {
        return [];
    }



    public function render($options,$fields, $showStart, $showFields, $showEnd)
    {
        $rendeerFields = [];
        if(!$this->has('attachment_title')){
            $renderFields   = [
                'attachment_title' => $this->makeField('attachment_title','text',['label'=>'Titolo'])
            ];
        }

        foreach($fields as $fieldName=>$field){
            $newName = "attachment_extrafields[{$fieldName}]";
            $field->setName($newName);
            $renderFields[$fieldName] = $field;
        }

        return parent::render($options,$renderFields,false,true,false);
    }

}
