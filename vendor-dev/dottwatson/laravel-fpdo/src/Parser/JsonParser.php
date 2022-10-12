<?php 
namespace Dottwatson\Fpdo\Parser;

use Dottwatson\Fpdo\Resource;
use Dottwatson\Fpdo\DataParser;
use Dottwatson\Fpdo\Exceptions\DataParserException;

class JsonParser extends DataParser{
    protected $type = 'json';

    /**
     * @inheritDoc
     */
    protected function reader()
    {
        $sourceData = Resource::acquire($this->source);
        
        if($sourceData === $this->source){
            $data = json_decode($sourceData,true);
            if(!is_array($data)){
                $data = [];
            }
        }
        else{
            $data = json_decode($sourceData,true);
            if(json_last_error() !== JSON_ERROR_NONE){
                throw new DataParserException("Error reading {$this->database}.{$this->table} as json: ".json_last_error_msg());
            }
        }
        
        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function writer()
    {
        if($this->isWritable()){
            $data = $this->toUTF8($this->data);

            $data = json_encode($data,JSON_PRETTY_PRINT);
            if(json_last_error() !== JSON_ERROR_NONE){
                throw new DataParserException("Error writing {$this->database}.{$this->table} as json: ".json_last_error_msg());
            }
            
            return file_put_contents($this->source,$data,LOCK_EX);    
        }
    
        return false;
    }

    /**
     * convert string or array into utf8
     *
     * @param mixed $data
     * @return mixed
     */
    public function toUTF8($data)
    {
        if (is_string($data)){
            return utf8_encode($data);
        }
        if (!is_array($data)){
            return $data;
        }
        
        $values = [];
        foreach ($data as $k=>$value)
            $values[$k] = $this->toUTF8($value);
        return $values;
    }

    // /**
    //  * Undocumented function
    //  *
    //  * @param [type] $data
    //  * @return void
    //  */
    // public function toUTF8Array($data)
    // {
    //     if (is_string($data)){
    //         return utf8_encode($data);
    //     }
    //     if (!is_array($data)){
    //         return $data;
    //     }

    //     $values = [];
    //     foreach ($data as $k=>$value){
    //         $values[$k] = $this->toUTF8Array($value);
    //     }

    //     return $values;
    // }

}

?>