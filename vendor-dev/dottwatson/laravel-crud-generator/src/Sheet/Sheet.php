<?php
namespace Dottwatson\CrudGenerator\Sheet;

use Dottwatson\CrudGenerator\Sheet\Traits\hasFields;
use Dottwatson\CrudGenerator\Sheet\Traits\hasGroups;
use Dottwatson\CrudGenerator\Sheet\Traits\hasTabBars;
use Illuminate\Contracts\View\Factory as View;
use Illuminate\Database\Eloquent\Model;


class Sheet
{
    
    use hasFields,hasGroups,hasTabBars;

    protected $view;
    protected $template;

    protected $model;

    public function __construct()
    {
        $this->template ='laravel-crud-generator::sheet.sheet';
    }
    
    public function buildSheet()
    {

    }

    /**
     * set current reference model
     *
     * @param Model $model
     * @return static
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * get current binded model
     *
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * Returns the view factory
     *
     * @return View
     */
    protected function getView()
    {
        return app(View::class);
    }

    /**
     * get defined view blade tempalate 
     *
     * @return string
     */
    protected function getTemplate()
    {
        return $this->template;
    }

    /**
     * set view blade tempalate 
     *
     * @param string $template
     * @return static
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }


    // /**
    //  * check if group is defined
    //  *
    //  * @param string $identifier
    //  * @return boolean
    //  */
    // public function isGroup(string $identifier)
    // {
    //     return isset($this->groups[$identifier]);
    // }

    // /**
    //  * set a group options
    //  *
    //  * @param string $identifier
    //  * @param array $options
    //  * @return static
    //  */
    // public function setGroupOptions(string $identifier = '',array $options = [])
    // {
    //     if($this->isGroup($identifier)){
    //         $this->groupOptions[$identifier] = array_merge(
    //             $this->groupOptions[$identifier],
    //             $options
    //         );
    //     }

    //     return $this;
    // }
    

    // /**
    //  * get options for group
    //  *
    //  * @param string $identifier
    //  * @param array $options
    //  * @return array|false
    //  */
    // public function getGroupOptions(string $identifier = '')
    // {
    //     if($this->isGroup($identifier)){
    //         $this->groupOptions[$identifier];
    //     }

    //     return false;
    // }

    // /**
    //  * add a group
    //  *
    //  * @param string $identifier
    //  * @param string $title
    //  * @param array $options
    //  * @param array $fields
    //  * @return static
    //  */
    // public function addGroup(string $identifier,string $title = '',array $options = [], array $fields = [])
    // {
    //     if(!$this->isGroup($identifier)){
    //         $this->groups[$identifier] = $title;
    //         $this->groupFields[$identifier] = [];

    //         if(!isset($options['size'])){
    //             $options['size'] = 12;
    //         }
    //         $this->groupOptions[$identifier] = $options;
    //     }

    //     $this->addGroupFields($identifier,$fields);

    //     return $this;
    // }

    // /**
    //  * remove a group and all its fields
    //  *
    //  * @param string $identifier
    //  * @return static
    //  */
    // public function removeGroup(string $identifier)
    // {
    //     if($this->isGroup($identifier)){
    //         unset($this->groupOptions[$identifier]);
    //         unset($this->groups[$identifier]);
    //         unset($this->groupsFields[$identifier]);
    //     }

    //     return $this;
    // }

    // /**
    //  * set a title for a given group
    //  *
    //  * @param string $identifier
    //  * @param string $title
    //  * @return static
    //  */
    // public function setGroupTitle(string $identifier,string $title)
    // {
    //     if($this->isGroup($identifier)){
    //         $this->groups[$identifier] = $title;
    //     }

    //     return $this;
    // }

    // /**
    //  * get the given group title
    //  *
    //  * @param string $identifier
    //  * @return string
    //  */
    // public function getGroupTitle(string $identifier)
    // {
    //     if($this->isGroup($identifier)){
    //         return $this->groups[$identifier];
    //     }

    //     return null;
    // }


    // /**
    //  * add a field to a group 
    //  *
    //  * @param string $groupIdentifier
    //  * @param string|Field $name
    //  * @param string $type
    //  * @param array $options
    //  * @return static
    //  */
    // public function addGroupField(string $groupIdentifier = '',$name, $type = 'text', array $options = [])
    // {
    //     if(!$this->isGroup($groupIdentifier)){
    //         $this->addGroup($groupIdentifier,'');
    //     }

    //     $this->groupFields[$groupIdentifier][] = $this->makeField($name, $type, $options);

    //     return $this;
    // }

    // /**
    //  * Add several fields to a group
    //  *
    //  * @param string $groupIdentifier
    //  * @param array $fields
    //  * @return static
    //  */
    // public function addGroupFields(string $groupIdentifier,array $fields = [])
    // {
    //     foreach($fields as $field){
    //         $this->addGroupField($groupIdentifier,...$field);
    //     }

    //     return $this;
    // }


    /**
     * get rendered sheet
     *
     * @return string
     */
    public function render()
    {
        $this->buildSheet();
        
        return $this->getView()
            ->make($this->getTemplate())
            ->with('model', $this->getModel())
            ->with('sheet', $this)
            ->with('fields',$this->fields)
            ->with('groups',$this->groups)
            ->with('tabBars',$this->tabBars)
            ->render();
    }


    /**
     * make a field for sheet
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * @return SheetField
     */
    public function makeField(string $name, string $type, array $options=[])
    {
        return New SheetField($name,$type,$options,$this);
    }


    /**
     * alias of makeField
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * @return SheetField
     */
    public function buildField(string $name, string $type, array $options=[])
    {
        return New SheetField($name,$type,$options,$this);
    }


    /**
     * returns the sheet rendered code
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

}
