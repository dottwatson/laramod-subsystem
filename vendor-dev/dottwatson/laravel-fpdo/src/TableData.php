<?php
namespace Dottwatson\Fpdo;


use Vimeo\MysqlEngine\TableData as FakeTableData;
use Dottwatson\Fpdo\Resource;

use Dottwatson\Fpdo\Parser\JsonParser;
use Dottwatson\Fpdo\Parser\CsvParser;
use Dottwatson\Fpdo\Parser\XmlParser;
use Dottwatson\Fpdo\Parser\ArrayParser;
use Illuminate\Support\Arr;

class TableData extends FakeTableData{

    const CSV_FORMAT       = 'csv';
    const JSON_FORMAT      = 'json';
    const ARRAY_FORMAT     = 'array';
    const XML_FORMAT       = 'xml';

    /**
     * The server
     *
     * @var Dottwatson\Fpdo\Server|null
     */
    protected $server;

    /**
     * The database name
     *
     * @var string
     */
    protected $databaseName;
    
    /**
     * The current table name
     *
     * @var string
     */
    protected $tableName;

    /**
     * The original table configuration 
     *
     * @var array
     */
    protected $tableConfig = [];


    /**
     * crate a table object
     *
     * @param Server $server
     * @param string $databaseName
     * @param string $tableName
     */
    public function __construct(Server $server,string $databaseName,string $tableName)
    {
        $this->server       = $server;
        $this->databaseName = $databaseName;
        $this->tableName    = $tableName;
        
        $this->tableConfig  = config("database.connections.{$this->databaseName}.tables.{$this->tableName}");

    }

    /**
     * get table configuration
     *
     * @return array
     */
    public function getTableConfig()
    {
        return $this->tableConfig;
    }

    /**
     * select appropriate parser from already available or binded
     *
     * @param string $database
     * @param string $table
     * @param int $mode 0 = read mode, 1 = write mode
     * @return Dottwatson\Fpdo\DataParser
     */
    public static function resolveParser(string $database, string $table,int $mode = 0)
    {
        $config = config("database.connections.{$database}.tables.{$table}");
        
        //try to read table info
        $dataType       = Arr::get($config,'type','text');
        $parser = null;

        switch($dataType){
            case self::JSON_FORMAT:
                $parser = new JsonParser($database,$table,$config,$mode);
            break;
            case self::CSV_FORMAT:
                $parser = new CsvParser($database,$table,$config,$mode);
            break;
            case self::XML_FORMAT:
                $parser = new XmlParser($database,$table,$config,$mode);
            break;
            case self::ARRAY_FORMAT:
                $parser = new ArrayParser($database,$table,$config,$mode);
            break;
            default:
                $binded = DataParser::getRegisteredParser();
                foreach($binded as $bindedParser=>$bindedClass){
                    if($dataType == $bindedParser){
                        $parser = new $bindedClass($database,$table,$config,$mode);
                        break;
                    }
                }
            break;
        }

        if($parser === null){
            throw new \Exception("{$dataType} is not a valid parser type");
        }

        return $parser;
    }

    

}