<?php 
namespace Dottwatson\Fpdo;

use Dottwatson\Fpdo\DataManager;
use Dottwatson\Fpdo\Exceptions\DataParserException;
use Exception;
use Illuminate\Support\Arr;

abstract class DataParser{

    const READ_MODE  = 0;
    const WRITE_MODE = 1;
    
    
    /**
     * the databse name
     *
     * @var string
     */
    protected $database;

    /**
     * the table name
     *
     * @var string
     */
    protected $table;


    /**
     * The source
     *
     * @var mixed
     */
    protected $source;

    /**
     * The source tyype
     *
     * @var string
     */
    protected $sourceType;

    /**
     * Flag to check if resource is writable
     *
     * @var bool
     */
    protected $writable;

    /**
     * Parser type
     *
     * @var string
     */
    protected $type;

    /**
     * Table configuration info
     *
     * @var array
     */
    protected $config = [];

    /**
     * The parser options
     *
     * @var array
     */
    protected $options = [];

   
    /**
     * The parsed data
     *
     * @var array
     */
    protected $data = [];
    
    /**
     * The custom parser registered
     *
     * @var array
     */
    protected static $bindedParsers = [];

   
    /**
     * constructor
     *
     * @param string $database
     * @param string $table
     * @param array $tableDefinition The table defonotopn in database connections config
     * @param integer $mode read (DataParser::READ_MODE) or write (DataParser::WRITE_MODE) 
     */
    public function __construct(string $database, string $table,array $tableDefinition,int $mode = 0)
    {
        $this->performInitialization($database,$table,$tableDefinition);
        
        if($mode == static::READ_MODE){
            $this->read();
        }
    }
    
    /**
     * initialize the parser without reading data
     *
     * @param string $database
     * @param string $table
     * @param array $tableDefinition
     * @return void
     */
    protected function performInitialization(string $database, string $table,array $tableDefinition)
    {
        $this->database     = $database;
        $this->table        = $table;
        $this->config       = $tableDefinition;
        
        $this->source       = $this->config('source');
        $this->sourceType   = Resource::getType($this->source);
        
        $options            = $this->config['options'] ?? [];
        $this->options      = array_replace_recursive($this->options,$options);

        $this->writable     = $this->config('write',false);
    }



    /**
     * Returns the source type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Return current source declared
     *
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Return current source type
     *
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }


    /**
     * getter - if source is a writable source
     *
     * @return boolean
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * Read data from source
     * @uses reader
     * @return bool
     */
    public function read()
    {
        $this->data = $this->reader();
    }

    /**
     * return original table 
     *
     * 
     * @return array
     */
    abstract protected function reader();




    /**
     * Re-read data from source. Any changes will be discarded
     *
     * @return bool
     */
    public function reload()
    {
        $this->data = $this->reader();
    }


    /**
     * Returns the data currently stored in table
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Create a parser and use it
     *
     * @param string $type the parser type
     * @param string $class the class where to bind the new parser
     * @return void
     */
    public static function register(string $type,string $class)
    {

        if(isset(self::$bindedParsers[$type])){
            throw new DataParserException("{$type} is already registered as dataparser");
        }
        elseif(!class_exists($class) || is_a($class,__CLASS__)){
            throw new DataParserException("{$type} is not a valid fpdo dataparser");
        }
        
        self::$bindedParsers[$type] = $class;
    }

    /**
     * Get the custom parse registered
     *
     * @return array
     */
    public static function getRegisteredParser(){
        return self::$bindedParsers;
    }


    /**
     * save data to table
     *
     * @param array $data
     * @return bool
     */
    protected function write(array $data)
    {
        $this->data = $data;
        return $this->writer();
    }

    /**
     * Perform the write operation
     *
     * @return bool
     */
    public function save(array $data)
    {

        if($this->isWritable()){
            return $this->write($data);
        }

        return true;
    }

    /**
     * write data on source, if writable
     *
     * @return bool
     */
    abstract protected function writer();

    /**
     * get config param or all if key is null or not defined
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function config(string $key = null,$default = null)
    {
        if($key === null){
            return $this->config;
        }
        else{
            return Arr::get($this->config,$key,$default);
        }
    }

    /**
     * get options param or all if key is null or not defined
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function options(string $key = null,$default = null)
    {
        if($key === null){
            return $this->options;
        }
        else{
            return Arr::get($this->options,$key,$default);
        }
    }


    // /**
    //  * Write data into resource if is writable
    //  * 
    //  */
    // public function __destruct()
    // {
    //     $this->write();
    // }



}

?>