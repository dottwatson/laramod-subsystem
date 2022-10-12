<?php 
namespace Dottwatson\Fpdo\Parser;

use Dottwatson\Fpdo\Resource;
use Dottwatson\Fpdo\DataParser;

class CsvParser extends DataParser{
    protected $type = 'csv';

    protected $headerColumns = [];


    protected $options = [
        'delimiter'     => ';',
        'enclosure'     => '"',
        'use_header'    => false,
    ];
    

    /**
     * @inheritDoc
     */
    protected function reader()
    {
        $rows = $this->parseCsvData();
        return $rows;
    }

    /**
     * @inheritDoc
     */
    protected function writer()
    {
        $this->parseCsvData();

        $csvRows = [];

        $columns = $this->headerColumns;

        if($this->options('use_header')){
            $csvRows[]=implode($this->options('delimiter'),$columns);
        }

        foreach($this->data as $row){
            $csvRows[] = implode($this->options('delimiter'),$row);
        }

        $data = implode("\n",$csvRows);
        return file_put_contents($this->source,$data,LOCK_EX);    
    }


    protected function parseCsvData()
    {
        $sourceData = Resource::acquire($this->source);

       
        $rowCnt         = 0;
        $prevLineEnding = ini_get('auto_detect_line_endings');
        
        ini_set('auto_detect_line_endings',TRUE);
        
        if($sourceData == ''){
            if($this->config('schema')){
                $sourceData = implode($this->options('delimiter'),array_keys($this->config('schema')));
            }
        }

        $handle = tmpfile();
        fwrite($handle,$sourceData);
        fseek($handle, 0);

        $headers    = [];
        $rows       = [];
        
        while (($dataRow = fgetcsv($handle, 1000,$this->options('delimiter'),$this->options('enclosure'))) !== FALSE) {
            if($rowCnt == 0){
                if($this->options('use_header')){
                    $headers = $dataRow;
                }
                else{
                    $rows[] = $dataRow;
                }
            }
            else{
                $rows[] = $dataRow;
            }

            $rowCnt++;
        }

        if($this->config('schema')){
            $headers = array_replace($headers,array_keys($this->config('schema')));
        }

        $maxColumns = 0;
        foreach($rows as $row){
            $maxColumns = (count($row) > $maxColumns)?count($row):$maxColumns;
        }

        foreach($rows as $k=>$row){
            if(count($row) < $maxColumns){
                $rows[$k] = array_pad($row,$maxColumns,null);
            }
        }

        if(count($headers) < $maxColumns){
            $headers = array_pad($headers,$maxColumns,null);
            foreach($headers as $kh => $header){
                if($header === null){
                    $headers[] = "Column".($kh+1);
                }
            }
        }

        $this->headerColumns = $headers;

        foreach($rows as $k=>$row){
            $rows[$k] = array_combine($this->headerColumns,$row);
        }

        fclose($handle);
        ini_set('auto_detect_line_endings',$prevLineEnding);

        return $rows;
    }


}
