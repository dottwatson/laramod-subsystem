<?php
namespace Dottwatson\CrudGenerator\Sheet;

use Dottwatson\CrudGenerator\Sheet\Traits\hasFields;
use Dottwatson\CrudGenerator\Sheet\Traits\hasGroups;
use Dottwatson\CrudGenerator\Sheet\Traits\hasTabBars;

use Illuminate\Contracts\View\Factory as View;


class Tab{
    use hasFields,hasGroups,hasTabBars;

    protected $label;
    protected $identifier;

    protected $sheet;

    public function __construct(string $label,string $identifier = null){
        $this->identifier = $identifier ?? uniqid();

        $this->label = $label;

        $this->setTemplate('laravel-crud-generator::sheet.tab');

    }

    public function setSheet(Sheet $sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    public function getSheet()
    {
        return $this->sheet;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }


    public function getIdentifier()
    {
        return $this->identifier;
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

    public function render(string $component = 'nav',bool $active = false)
    {

        return app(View::class)
            ->make($this->getTemplate())
            ->with('tab', $this)
            ->with('fields', $this->fields)
            ->with('groups',$this->groups)
            ->with('tabBars',$this->tabBars)
            // ->with('options',$this->options)
            ->with('component',$component)
            ->with('active',$active)
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