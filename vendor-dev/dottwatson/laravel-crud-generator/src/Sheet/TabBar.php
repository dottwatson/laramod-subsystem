<?php
namespace Dottwatson\CrudGenerator\Sheet;

use Illuminate\Contracts\View\Factory as View;


class TabBar{

    protected $identifier;
    protected $tabs=[];

    protected $sheet;

    public function __construct(string $identifier = null)
    {
        $this->identifier = $identifier ?? uniqid();
        $this->setTemplate('laravel-crud-generator::sheet.tabBar');
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function addTab(string $label,string $identifier = null){
        $identifier = $identifier ?? uniqid();

        $tab = new Tab($label,$identifier);
        $tab->setSheet($this->getSheet());
        $this->tabs[$identifier] = $tab;

        return $this->tabs[$identifier];
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


    public function getTab(string $identifier)
    {
        return $this->tabs[$identifier] ?? null;
    }

    public function getTabs()
    {
        return $this->tabs;
    }

    public function removeTab(string $identifier)
    {
        unset($this->tabs[$identifier]);

        return $this;
    }

    public function removeTabs()
    {
        $this->tabs = [];

        return $this;
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


    public function getNestedFields(array $nestedFields = [])
    {
        $fields = $nestedFields;
        foreach($this->tabs as $tab){
            $fields = array_merge($fields,$tab->getNestedFields($fields));
        }

        return $fields;
    }

    public function render()
    {

        return app(View::class)
            ->make($this->getTemplate())
            ->with('tabBar', $this)
            ->with('tabs',$this->tabs)
            ->render();
    }


}