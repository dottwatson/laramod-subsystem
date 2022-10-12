<?php 
namespace Dottwatson\DatatableGenerator;

use Illuminate\Config\Repository as OptionsParameters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Yajra\DataTables\Facades\DataTables;

class Table{
    protected static $addResources = true;

    /**
     * the query builder
     *
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * table columns
     *
     * @var array
     */
    protected $columns = [];

    /**
     * topbar buttons
     *
     * @var array
     */
    protected $buttons = [];

    /**
     * table ajax endpoint
     *
     * @var string|null
     */
    protected $endpoint;

    /**
     * the options
     *
     * @var Illuminate\Config\Repository
     */
    protected $options;

    protected $id;


    public function __construct()
    {
        $this->options = new OptionsParameters([]);
        $this->addButtons(config('common.datatables_buttons',[]));

        $this->id = uniqid();
    }

    /**
     * init the table 
     *
     * @return static
     */
    public function init()
    {

    }

    /**
     * set the query builder for table
     *
     * @param Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder $builder
     * @return static
     */
    public function setQuery($builder)
    {
        $this->queryBuilder = $builder;

        return $this;
    }

    /**
     * returns query builder
     *
     * @return Builder
     */
    public function getQuery()
    {
        return $this->queryBuilder;
    }

    /**
     * add a column 
     *
     * @param string|TableColumn $column
     * @param string|null $label
     * @param string|null $modifier
     * @param array $modifierArguments
     * @return static
     */
    public function addColumn($column,string $label = null,string $modifier = null,array $modifierArguments = [])
    {
        if($column instanceOf TableColumn){
            $this->columns[ $column->getName() ] = $column;
        }
        else{
            $tableColumn = new TableColumn($column,$label);
            if($modifier){
                call_user_func_array(
                    [$tableColumn,'as'.$modifier],
                    $modifierArguments
                );
            }
            $this->columns[ $column ] = $tableColumn;
        }

        
        return $this;
    }

    /**
     * Add columns
     *
     * @param array $columns
     * @return static
     */
    public function addColumns(array $columns = [])
    {
        foreach($columns as $column){
            if(is_array($column)){
                $this->addColumn(...$column);
            }
            else{
                $this->addColumn($column);
            }
        }

        return $this;
    }

    /**
     * add a button on the table top button bar
     *
     * @param TableButton|string $button
     * @param array $options fi is string the button
     * @return static
     */
    public function addButton($button,array $options = [])
    {
        if($button instanceof TableButton){
            $this->buttons[] = $button;
        }
        else{
            $this->buttons[] = new TableButton($button,$options);
        }
    
        return $this;
    }

    /**
     * add several buttons
     * 
     * @param array $buttons
     * @return static
     */
    public function addButtons(array $buttons = [])
    {
        foreach($buttons as $button){
            if(is_array($button)){
                $this->addButton(...$button);
            }
            elseif($button instanceof TableButton){
                $this->addButton($button);
            }
            else{
                throw new \Exception("Button must be instance of TableButton or an array");
            }
        }

        return $this;
    }

    /**
     * Remove all buttons
     *
     * @return static
     */
    public function removeButtons()
    {
        $this->buttons = [];

        return $this;
    }

    /**
     * get all attached topbar buttons
     *
     * @return array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * Add a column after another column identified by its name
     *
     * @param string $name
     * @param TableColumn $column
     * @return static
     */
    public function addColumnAfter(string $name,TableColumn $column)
    {
        $columns = [];
        $inserted = false;
        foreach($this->columns  as $collectionName=>$collectionColumn){
            $columns[$collectionName] = $collectionColumn;

            if($collectionName == $name){
                $columns[$column->getName()] = $column;               
                $inserted = true;
            }
        }

        if(!$inserted){
            $columns[$column->getName()] = $column;               
        }


        $this->columns = $columns;

        return $this;
    }

    /**
     * Add a column before another column identified by its name
     *
     * @param string $name
     * @param TableColumn $column
     * @return static
     */

    public function addColumnBefore(string $name,TableColumn $column)
    {
        $columns = [];
        $inserted = false;
        foreach($this->columns  as $collectionName=>$collectionColumn){
            if($collectionName == $name){
                $columns[$column->getName()] = $column;               
                $inserted = true;
            }

            $columns[$collectionName] = $collectionColumn;
        }

        if(!$inserted){
            $columns[$column->getName()] = $column;               
        }

        $this->columns = $columns;

        return $this;
    }

    /**
     * Get column instance identfied by name
     *
     * @param string $name
     * @return TableColumn|null
     */
    public function getColumn(string $name)
    {
        return $this->columns[$name] ?? null;
    }


    /**
     * Set the ajax endpoint for table
     *
     * @param string $endpoint
     * @return static
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint  = $endpoint;

        return $this;
    }

    /**
     * get current endpoint
     *
     * @return string|null
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }


    /**
     * get table defined option
     *
     * @param string $target
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $target,$default = null)
    {
        return $this->options->get($target,$default);
    }


    /**
     * set/add an option in table definition
     *
     * @param string|array $target if array is a key=>value pair options list
     * @param mixed $value
     * @return void
     */
    public function setOption($target,$value = null)
    {
        $this->options->set($target,$value);
    
        return $this;
    }

    /**
     * get current defined options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options->all();
    }

    
    /**
     * get all columns defined in table
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * get columns js definition for table
     *
     * @return array
     */
    public function getScriptColumns()
    {
        $columns = [];
        foreach($this->columns as $name=>$column){
            $columns[] = $column->toTableData();
        }

        return $columns;
    }

    /**
     * returns current table id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * returns all assets for table and table js definition
     *
     * @param string|null $tableId
     * @return string
     */
    public function scripts(string $tableId = null)
    {
        $tableId = (is_null($tableId))?$this->id:$tableId;
        $result = view('laravel-datatable-generator::scripts',[
            'table'=>$this,
            'id'=>$tableId,
            'addResources'=>self::$addResources
        ]);

        self::$addResources = false;

        return $result;
    }

    /**
     * returns html table definition
     *
     * @param string|null $tableId
     * @return string
     */
    public function table(string $tableId = null)
    {
        if($tableId){
            $this->id = $tableId;
        }
        return view('laravel-datatable-generator::table',['table'=>$this,'id'=>$this->id]);
    }

    /**
     * Enable/disable search builder on table
     *
     * @param boolean $status
     * @return static
     */
    public function withSearchBuilder(bool $status = true)
    {
        $this->setOption('searchBuilder',$status);

        return $this;
    }
    
    
    /**
     * resolve the table ajax request 
     *
     * @return array
     */
    public function getData()
    {
        $request    = request();
        $query = $this->queryBuilder;

        if(!isset($query->columns) || !$query->columns){
            $query->addSelect('*');
        }
        // $query      = $this->queryBuilder->addSelect('*');

        $rawColumns     = [];
        $builderColumns = [];
        $builderFilters = [];

        foreach($this->columns as $name=>$column){
            if($column->isCustom()){
                $query->addSelect(DB::raw("'' AS {$name}"));
                $builderColumns[]   = [$name,$column->getContent()];
                $rawColumns[]       = $name;
            }
            elseif($column->getContent()){
                $builderColumns[]   = [$name,$column->getContent()];
                $rawColumns[]       = $name;

                if($column->getSearchable()){
                    if($column->getFilter()){
                        $builderFilters[] = [$name,$column->getFilter()];
                    }
                    else{
                        $builderFilters[] = [$name,function($query,$keyword) use ($name){
                            $query->orWhere($name,'LIKE',"%{$keyword}%");
                        }];
                    }                    
                }
            }
            else{
                if($column->getSearchable() && $column->getFilter()){
                    $builderFilters[] = [$name,$column->getFilter()];
                }
            }
        }

        if($this->getOption('searchBuilder') && $request->input('searchBuilder')){
            //parser to build query
            $criterias = $request->input('searchBuilder');
            $this->addSearchBuilderCriterias($criterias,$query);

        }

        $builder = DataTables::of($query);
        $builder->addIndexColumn();

        foreach($builderColumns as $column){
            call_user_func_array([$builder,'addColumn'],$column);
        }

        foreach($builderFilters as $filter){
            call_user_func_array([$builder,'filterColumn'],$filter);
        }

        foreach($this->columns as $name=>$column){
            $builder->orderColumn($name, $column->getSortAs());
        }

        if($rawColumns){
            $builder->rawColumns($rawColumns);
        }

        return $builder->make(true);
    }

    /**
     * add criterias to query builder, based on search builder 
     *
     * @param array $criterias
     * @param Builder $query
     * @return void
     */
    public function addSearchBuilderCriterias($criterias,$query){
        $self = $this;
        $logic = ($criterias['logic'] == 'AND')?'where':'orWhere';
        foreach($criterias['criteria'] as $criteria){
            if(isset($criteria['criteria'])){
                $query->{$logic}(function($query) use ($self,$criteria){
                    $self->addSearchBuilderCriterias($criteria,$query);                    
                });
            }
            else{
                $condition = $self->buildSearchBuilderCondition($criteria['condition'],$criteria['value']);
                
                $query->{$logic}($criteria['origData'],$condition[0],$condition[1]);
            }
        }
    }

    /**
     * Used internally from addSearchBuilderCriterias
     *
     * @param string $condition
     * @param mixed $value
     * @return array
     */
    public function buildSearchBuilderCondition($condition,$value)
    {
        switch($condition){
            case 'starts':
                $value = array_shift($value);
                return ['LIKE',$value.'%'];
            break;
            case 'ends':
                $value = array_shift($value);
                return ['LIKE','%'.$value];
            break;
        }

        return [$condition,$value];
    }




    /**
     * preserve json functions
     * each function must be encolsed in #!! and !!#
     *
     * @param mixed $obj
     * @return string
     */
    public static function jsonEncode($obj){
        $string = json_encode($obj,JSON_PRETTY_PRINT);
        preg_match_all('~"#!!(?P<code>.+)!!#"~Usmi',$string,$codes);
        // var_dump($string,$codes);die();
        if($codes['code']){
            foreach($codes['code'] as $code){
                $cleanCode = str_replace(["\\n"],"\n",$code);
                $string = str_replace($code,$cleanCode,$string);
            }
        }

        $string = str_replace('"#!!','',$string);
        $string = str_replace('!!#"','',$string);

        // var_dump($string);die();

        return  $string;
    }


}


?>