<?php
namespace Dottwatson\CrudGenerator\Form;

use Dottwatson\CrudGenerator\Form\Traits\hasFields;
use Dottwatson\CrudGenerator\Form\Traits\hasGroups;
use Dottwatson\CrudGenerator\Form\Traits\hasTabBars;

use Illuminate\Contracts\View\Factory as View;


class Group{

    use hasFields,hasTabBars,hasGroups;

    protected $label;
    protected $options = [];

    protected $identifier;

    protected $template;

    protected $form;

    public function __construct(string $label,string $identifier = null,array $options = []){
        $this->identifier = $identifier ?? uniqid();

        $this->label    = $label;
        $this->options  = $options;

        $this->setTemplate('laravel-crud-generator::form.group');
        
    }

    public function setForm(Form $form)
    {
        $this->form = $form;

        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setOptions(array $options = [])
    {
        $this->options = array_merge($this->options,$options);

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }


    public function render()
    {

        return app(View::class)
            ->make($this->getTemplate())
            ->with('group', $this)
            ->with('fields', $this->fields)
            ->with('tabBars',$this->tabBars)
            ->with('groups',$this->groups)
            ->with('options',$this->options)
            ->render();
    }

    public function getNestedFields(array $nestedFields = [])
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

}