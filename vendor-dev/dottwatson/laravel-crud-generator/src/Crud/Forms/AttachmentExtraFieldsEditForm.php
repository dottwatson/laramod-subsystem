<?php
namespace Dottwatson\CrudGenerator\Crud\Forms;

use Dottwatson\CrudGenerator\Form\Form;

class AttachmentExtraFieldsEditForm extends Form
{
   
    /**
     * the extrafieldsform
     *
     * @var Form
     */
    protected $extrafieldsForm;

    protected function getTemplate()
    {
        return 'laravel-crud-generator::form.attachment-extrafields-form';
    }
    
    public function buildForm()
    {
        return [];
    }

    public function fromExtrafieldsForm(Form $form)
    {
        $this->extrafieldsForm = $form;

        return $this;
    }

    public function render($options,$fields, $showStart, $showFields, $showEnd)
    {
        $model      = $this->getModel();
        $extradata  = $model->extrafields;
        $extradata  = ($extradata)?$extradata:[];

        $_tokenEditAttachment = encrypt([
            'model' => get_class($model),
            'id'    => $model->getKey()
        ]);
        
        $uniqId = uniqid('ae_');
        $endFieldName = 'attachment_edit['.$uniqId.'][%s]';

        $renderFields   = [
            'attachment_title' => $this->makeField('attachment_title','text',['label'=>'Titolo','value'=>$model->title]),
            '_tokenEditAttachment' => $this->makeField('attachment_title','hidden',['value'=>$_tokenEditAttachment])
        ];

        foreach($this->extrafieldsForm->getFields() as $fieldName=>$field){
            $field->setValue($extradata[$fieldName] ?? null);
            $renderFields[$fieldName] = $field;
        }


        foreach($renderFields as $fieldName=>$field){
            $renderFields[$fieldName]->setName(sprintf($endFieldName,$fieldName));

        }

        return parent::render($options,$renderFields,false,true,false);
    }

}
