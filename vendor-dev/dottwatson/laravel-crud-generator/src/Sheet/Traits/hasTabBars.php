<?php
namespace Dottwatson\CrudGenerator\Sheet\Traits;

use Dottwatson\CrudGenerator\Sheet\TabBar;

trait hasTabBars{
    protected $tabBars = [];


    public function addTabBar(string $identifier = null)
    {
        $item = new TabBar($identifier);
        $this->tabBars[$item->getIdentifier()] = $item;

        if(is_a($this,Sheet::class)){
            $item->setSheet($this);
        }
        else{
            $item->setSheet($this->getSheet());
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