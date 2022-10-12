<?php
namespace Dottwatson\CrudGenerator\Form\Traits;

use Dottwatson\CrudGenerator\Form\TabBar;

trait hasTabBars{
    protected $tabBars = [];


    public function addTabBar(string $identifier = null)
    {
        $item = new TabBar($identifier);
        $this->tabBars[$item->getIdentifier()] = $item;

        if(is_a($this,Form::class)){
            $item->setForm($this);
        }
        else{
            $item->setForm($this->getForm());
        }


        return $this->tabBars[$item->getIdentifier()];
    }

    public function getTabBar(string $identifier)
    {
        return $this->tabBars[$identifier] ?? null;
    }

    public function removeTabBar(string $identifier)
    {
        unset($this->tabBars[$identifier]);

        return $this;
    }

    public function getTabBars()
    {
        return $this->tabBars;
    }
}