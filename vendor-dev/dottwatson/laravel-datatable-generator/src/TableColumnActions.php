<?php

namespace Dottwatson\DatatableGenerator;

use Closure;

class TableColumnActions extends TableCustomColumn{

    protected $actions = [];
    
    public function __construct(string $name,string $label = null)
    {
        parent::__construct($name,$label);

        $this->setSearchable(false);
        $this->setSortable(false);
    }


    public function addAction(Closure $action)
    {
        $this->actions[]=$action;

        return $this;
    }


    public function getContent()
    {
        $actions = $this->actions;
        $contentCallback = function($row) use($actions){
            $columnActions = [];
            foreach($actions as $action){
                $columnActions[] = $action($row);
            }
        
            return TableHelper::actions($columnActions);
        };

        $this->setContent($contentCallback);

        return parent::getContent();
    }


    


}