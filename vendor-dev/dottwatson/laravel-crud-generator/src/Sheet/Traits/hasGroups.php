<?php
namespace Dottwatson\CrudGenerator\Sheet\Traits;

use Dottwatson\CrudGenerator\Sheet\Group;
use Dottwatson\CrudGenerator\Sheet\Sheet;

trait hasGroups{
    protected $groups = [];


    public function addGroup(string $label = '',string $identifier = null,array $options = [])
    {
        $item = new Group($label,$identifier,$options);

        
        if(is_a($this,Sheet::class)){
            $item->setSheet($this);
        }
        else{
            $item->setSheet($this->getSheet());
        }

        $this->groups[$item->getIdentifier()] = $item;

        return $this->groups[$item->getIdentifier()];
    }

    public function getGroup(string $identifier)
    {
        return $this->groups[$identifier] ?? null;
    }

    public function removeGroup(string $identifier)
    {
        unset($this->groups[$identifier]);

        return $this;
    }

    public function getGroups()
    {
        return $this->groups;        
    }
}