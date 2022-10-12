<?php
namespace Dottwatson\CrudGenerator\Form;

use Dottwatson\CrudGenerator\Form\Traits\hasFields;
use Dottwatson\CrudGenerator\Form\Traits\hasGroups;
use Dottwatson\CrudGenerator\Form\Traits\hasTabBars;

use Kris\LaravelFormBuilder\Form as LaravelFormBuilderForm;
use Kris\LaravelFormBuilder\Events\AfterFieldCreation;
use Kris\LaravelFormBuilder\Events\AfterFormValidation;
use Kris\LaravelFormBuilder\Events\BeforeFormValidation;


class Form extends LaravelFormBuilderForm
{
    use hasGroups,hasTabBars;
    
    protected $actions = [];

    public function addFields(array $fields = [])
    {
        foreach($fields as $field){
            $field = $this->makeField(...$field);
            $this->addField($field);
        }
    
        return $this;
    }

    public function prependFields(array $fields = [])
    {
        $newFields = [];
        foreach($fields as $field){
            $field          = $this->makeField(...$field);
            $newFields[]    = $field->getRealName();
            $this->addField($field);
        }

        //rebuild fields list, with newly creted ad top of array;
        $endFields = [];
        foreach($newFields as $fName){
            $endFields[$fName] = $this->fields[$fName];
        }

        foreach($this->fields as $fName=>$field){
            if(!isset($endFields[$fName])){
                $endFields[$fName] = $field;
            }
        }

        $this->fields = $endFields;

        return $this;
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


    public function addSubmit(string $label = null, string $name = null,array $options = [])
    {
        $label                      = (is_null($label)?__('save'):$label);
        $name                       = (is_null($name)?'save_form':$name);
        $options['icon']            = '<i class="fa fa-check"></i>';
        $options['attr']            = $options['attr'] ?? [];
        $options['attr']['name']    = $name;
        $options['wrapped']         = false;
        
        $this->actions['submit'] = $this->makeField($label, 'submit',$options);

        return $this;
    }

    /**
     * Get all form field attributes, including child forms, in a flat array.
     *
     * @return array
     */
    public function getAllAttributes()
    {
        $fields = $this->getMergedFieldsWithChilds();

        foreach($fields as $k=>$field){
            if($field->getType() == 'attachments'){
                unset($fields[$k]);
            }
        }
        return $this->formHelper->mergeAttributes($fields);
    }

    
    public function hasAttachmentsFields(){
        $fields = $this->getMergedFieldsWithChilds();
        foreach($fields as $k=>$field){
            if($field->getType() == 'attachments'){
                return true;
            }
        }
        return false;
    }

    /**
     * Get single field instance from form object.
     *
     * @param string $name
     * @return FormField
     */
    public function getField($name)
    {
        if ($this->has($name)) {
            $fields = $this->getMergedFieldsWithChilds();
            return $fields[$name];
        }

        $this->fieldDoesNotExist($name);
    }


    /**
     * Get validation rules for the form.
     *
     * @param array $overrideRules
     * @return array
     */
    public function getRules($overrideRules = [])
    {
        $fields = $this->getMergedFieldsWithChilds();
        $fieldRules = $this->formHelper->mergeFieldsRules($fields);

        return array_merge($fieldRules->getRules(), $overrideRules);
    }

    /**
     * Validate the form.
     *
     * @param array $validationRules
     * @param array $messages
     * @return Validator
     */
    public function validate($validationRules = [], $messages = [])
    {
        $fields = $this->getMergedFieldsWithChilds();

        $fieldRules = $this->formHelper->mergeFieldsRules($fields);
        $rules = array_merge($fieldRules->getRules(), $validationRules);
        $messages = array_merge($fieldRules->getMessages(), $messages);

        $this->validator = $this->validatorFactory->make($this->getRequest()->all(), $rules, $messages);
        $this->validator->setAttributeNames($fieldRules->getAttributes());

        $this->eventDispatcher->dispatch(new BeforeFormValidation($this, $this->validator));

        return $this->validator;
    }



    /**
     * Check if form has field.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        $fields = $this->getMergedFieldsWithChilds();

        return isset($fields[$name]);
    }

    protected function getMergedFieldsWithChilds()
    {
        return $this->getNestedFields();
    }

    protected function getNestedFields(array $nestedFields = [])
    {
        $fields = $nestedFields;
        foreach($this->fields as $k=>$field){
            $fields[$k] = $field; 
        }

        foreach($this->groups as $group){
            $fields = array_merge($fields,$group->getNestedFields($fields));
        }

        foreach($this->tabBars as $tabBar){
            $fields = array_merge($fields,$tabBar->getNestedFields($fields));
        }

        return $fields;
    }

    public function getAttachmentsFields()
    {
        $fields = $this->getMergedFieldsWithChilds();

        foreach($fields as $k=>$field){
            if($field->getType() != 'attachments'){
                unset($fields[$k]);
            }
        }

        return $fields;
    }


    public function removeSubmit()
    {
        unset($this->actions['submit']);

        return $this;
    }


    public function addReset(string $label = null,array $options = [])
    {
        $label                      = (is_null($label)?__('reset'):$label);
        $options['icon']            = '<i class="fa fa-eraser"></i>';
        $options['wrapped']         = false;

        $this->actions['reset'] = $this->makeField($label, 'reset',$options);

        return $this;
    }

    public function removeReset()
    {
        unset($this->actions['reset']);

        return $this;
    }

    public function addBack(string $label = null, string $target = 'javascript:history.back()',array $options = [])
    {
        if(is_null($target)){
            $target = 'javascript:history.back()';
        }
        else{
            $target = "location.href='{$target}'";
        }

        $label                      = (is_null($label)?__('back'):$label);
        $options['attr']            = $options['attr'] ?? [];
        $options['attr']['class']   = 'btn btn-primary';
        $options['attr']['onClick'] = $target;
        $options['icon']            = '<i class="fa fa-arrow-left"></i>';
        $options['wrapped']         = false;
        
        $this->actions['back'] = $this->makeField($label, 'button', $options);

        return $this;
    }

    public function removeBack()
    {
        unset($this->actions['back']);

        return $this;
    }

    public function buildField($name,$type,array $options = [])
    {
        return $this->makeField($name,$type,$options);
    }

    protected function render($options, $fields, $showStart, $showFields, $showEnd)
    {
        $formOptions = $this->buildFormOptionsForFormBuilder(
            $this->formHelper->mergeOptions($this->formOptions, $options)
        );

        $this->setupNamedModel();

        return $this->formHelper->getView()
            ->make($this->getTemplate())
            ->with(compact('showStart', 'showFields', 'showEnd'))
            ->with('formOptions', $formOptions)
            ->with('model', $this->getModel())
            ->with('exclude', $this->exclude)
            ->with('form', $this)
            ->with('fields', $fields)
            ->with('groups',$this->groups)
            ->with('tabBars',$this->tabBars)
            ->with('actions',$this->actions)
            ->render();
    }

}
