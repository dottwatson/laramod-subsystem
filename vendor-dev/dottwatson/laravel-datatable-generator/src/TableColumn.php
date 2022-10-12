<?php

namespace Dottwatson\DatatableGenerator;

use Illuminate\Support\Carbon;


class TableColumn{

    /**
     * name attribute
     *
     * @var string
     */
    protected $name;


    /**
     * data attribute
     *
     * @var string
     */
    protected $data;
    /**
     * content modifier
     *
     * @var callable
     */
    protected $content;
    /**
     * sortable attribute
     *
     * @var bool
     */
    protected $sortable = true;
    /**
     * searchable attribute
     *
     * @var bool
     */
    protected $searchable = true;
    /**
     * label attribute
     *
     * @var string
     */
    protected $label;

    /**
     * meta flag
     *
     * @var boolean
     */
    protected $meta = false;
    
    /**
     * custom column flag
     *
     * @var boolean
     */
    protected $custom = false;

    protected $sortAs;

    protected $filter;


    /**
     * cretae a column
     *
     * @param string $name
     * @param string|null $label
     */
    public function __construct(string $name,string $label = null)
    {
        $this->name = $name;
        $this->label = $label;

        $this->sortAs($this->name);
    }

    /**
     * get column name attribute
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    // /**
    //  * Check if is a meta field for entity
    //  *
    //  * @return boolean
    //  */
    // public function isMeta()
    // {
    //     return $this->meta === true;
    // }

    /**
     * Chck if is a custom column in resultset
     *
     * @return boolean
     */
    public function isCustom()
    {
        return $this->custom === true;
    }

    /**
     * set column data attribute
     *
     * @param string $value
     * @return static
     */
    public function setData(string $value)
    {
        $this->data = $value;

        return $this;
    }

    /**
     * get column data attribute
     *
     * @param string $value
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * set callable modifier for column
     *
     * @param callable $value
     * @param array $contentArgs
     * @return static
     */
    public function setContent(callable $value,array $contentArgs = [])
    {
        $this->content          = $value;
        $this->contentArguments = $contentArgs;

        return $this;
    }

    /**
     * returns column modifier
     *
     * @return callable
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * set column searchable attribute
     *
     * @param bool $value
     * @return static
     */
    public function setSearchable(bool $value)
    {
        $this->searchable = $value;

        return $this;
    }

    /**
     * get column searchable attribute
     *
     * @return bool
     */
    public function getSearchable()
    {
        return $this->searchable;
    }


    /**
     * set column sortable attribute
     *
     * @param bool $value
     * @return static
     */
    public function setSortable(bool $value)
    {
        $this->sortable = $value;

        return $this;
    }

    /**
     * get column sortable attribute
     *
     * @return bool
     */
    public function getSortable()
    {
        return $this->sortable;
    }


    /**
     * set column lable attribute
     *
     * @param string $value
     * @return static
     */
    public function setLabel(string $value)
    {
        $this->label = $value;

        return $this;
    }

    /**
     * get column label attribute
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }



    public static function id($columnId)
    {
        $column = new static('DT_RowId');
        $column->setContent(function($row) use ($columnId){
            if(is_callable($columnId)){
                return call_user_func($columnId,$row);
            }    
            else{
                return $row->{$columnId};
            }
        });

        return $column;
    }



    /**
     * Export columns for javascript table 
     *
     * @return array
     */
    public function toTableData()
    {
        return [
            'name'=>$this->name,
            'data'=>$this->data ?? $this->name,
            'searchable'=>$this->searchable,
            'orderable'=>$this->sortable
        ];
    }

    /**
     * Export column data for table header
     *
     * @return array
     */
    public function toHTMLTable()
    {
        return [
            'name'=>$this->name,
            'label'=>($this->label)?$this->label:$this->name
        ];
    }


    /**
     * Cretae column from base array
     *
     * @param array $column
     * @return static
     */
    public static function fromArray(array $column = [])
    {
        $instance   = new static('');
        $fields     = ['name','meta','data','content','sortable','searchable','label'];
        
        foreach($column as $name=>$value){
            if(method_exists($instance,'set'.$name)){
                call_user_func([$instance,'set'.$name],$value);
            }
        }

        return $instance;
    }

    /**
     * set sorting field
     *
     * @param string $name
     * @return static
     */
    public function sortAs(string $name)
    {
        $this->sortAs = "{$name} $1";

        return $this;
    }


    /**
     * get sort as field
     *
     * @return string
     */
    public function getSortAs()
    {
        return $this->sortAs;
    }


    /**
     * set a closure to be executed when search is perfomed
     *
     * @param array|Closure $filter
     * @return static
     */
    public function setFilter(callable $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * get current closuer used on search query 
     *
     * @return null|array|Closure
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * helper modifier for convert column into date format
     *
     * @param string|null $format
     * @return static
     */
    public function asDate(string $format = null)
    {
        $format = $format ?? 'Y-m-d H:i:s';
        $field = $this->name;

        $this->setContent(function($row) use($field,$format){
            return Carbon::parse($row->{$field})->format($format);
        });
        
        return $this;
    }

    /**
     * helper modifier for convert column into mail link format
     *
     * @param string $subject
     * @param string $body
     * @return static
     */
    public function asEmail(string $subject = '',string $body = '')
    {
        $format = $format ?? 'Y-m-d H:i:s';
        $field = $this->name;

        $this->setContent(function($row) use($field,$subject,$body){
            $target = $row->{$field};
            
            $mailData = http_build_query(['subject'=>$subject,'body'=>$body]);

            return '<a href="mailto:'.$target.'?'.$mailData.'">'.$target.'</a>';
        });

        return $this;
    }

    /**
     * helper modifier for convert column into phone link format
     *
     * @return static
     */
    public function asPhone()
    {
        $field = $this->name;

        $this->setContent(function($row) use($field){
            $target = $row->{$field};

            return '<a href="tel:'.$target.'">'.$target.'</a>';
        });

        return $this;
    }

    /**
     * helper modifier for convert column into boolean icon format
     *
     * @return static
     */
    public function asBoolean()
    {
        $field = $this->name;

        $this->setContent(function($row) use($field){
            $target = $row->{$field};

            if($target == true){
                return '<div class="text-center"><i class="fa fa-check text-success"></i></div>';
            }

            return '<div class="text-center"><i class="fa fa-times text-danger"></i></div>';
        });

        return $this;
    }


}