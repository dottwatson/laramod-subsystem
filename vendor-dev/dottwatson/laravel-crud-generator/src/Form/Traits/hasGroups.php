<?php
namespace Dottwatson\CrudGenerator\Form\Traits;

use Dottwatson\CrudGenerator\Form\Group;
use Dottwatson\CrudGenerator\Form\Form;

trait hasGroups{
    protected $groups = [];


    public function addGroup(string $label = '',string $identifier = null,array $options = [])
    {
        $item = new Group($label,$identifier,$options);

        
        if(is_a($this,Form::class)){
            $item->setForm($this);
        }
        else{
            $item->setForm($this->getForm());
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