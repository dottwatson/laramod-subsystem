<?php 
namespace Dottwatson\DatatableGenerator;

use Dottwatson\DatatableGenerator\Table;

class TableButton{
    // 'excel',
    // 'pdf',
    // 'print',
    // 'copy',
    // 'colvis',

    /**
     * button type (excel,pdf,print,copy,colvis)
     *
     * @var string
     */
    public $type;

    /**
     * Button options
     *
     * @var array
     */
    protected $options = [];    
    
    public function __construct($type,array $options = [])
    {
        $this->type     = $type;
        $this->options  = $options;
    }


    /**
     * shortcut of pdf type button
     *
     * @param array $options
     * @return static
     */
    public static function pdf(array $options = [])
    {
        $button = (new static('pdf',$options));

        return $button;
    }

    /**
     * shortcut of excel type button
     *
     * @param array $options
     * @return static
     */
    public static function excel(array $options = [])
    {
        $button = (new static('excel',$options));

        return $button;
    }

    /**
     * shortcut of print type button
     *
     * @param array $options
     * @return static
     */
    public static function print(array $options = [])
    {
        $button = (new static('print',$options));

        return $button;
    }

    /**
     * shortcut of csv type button
     *
     * @param array $options
     * @return static
     */
    public static function csv(array $options = [])
    {
        $button = (new static('csv',$options));

        return $button;
    }

    /**
     * shortcut of copy type button
     *
     * @param array $options
     * @return static
     */
    public static function copy(array $options = [])
    {
        $button = (new static('copy',$options));

        return $button;
    }

    /**
     * shortcut of colvis type button
     *
     * @param array $options
     * @return static
     */
    public static function colvis(array $options = [])
    {
        $button = (new static('colvis',$options));

        return $button;
    }

    /**
     * preserve json functions
     * each function must be encolsed in #!! and !!#
     *
     * @param mixed $obj
     * @return string
     */
    protected static function jsonEncode($obj){
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


    /**
     * set array options
     *
     * @param array $options
     * @return static
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * render the button
     *
     * @return string
     */
    public function render()
    {
        if($this->options){
            $defaultOptions = [
                'extend'=>$this->type,
                'text' => $this->options['text'] ?? strtoupper($this->type),
            ];

            $options = array_merge($defaultOptions,$this->options);
            return static::jsonEncode($options);
        }
        else{
            return json_encode($this->type);
        }
    }


}
?>