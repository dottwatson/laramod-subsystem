<?php
namespace Dottwatson\CrudGenerator\Crud\Sheets;

use Illuminate\Database\Eloquent\Model;

class AttachmentExtraFieldsSheetData{

    public function __construct(Model $model = null){
        $data = [];

        if($model){
            $extrafields = $model->extrafields;

            if(is_array($extrafields)){
                $data = $extrafields;
            }
            elseif(is_object($extrafields)){
                $data = get_object_vars($extrafields);
            }
        }

        foreach($data as $k=>$v){
            $this->{$k} = $v;
        }
    }


    public function __get($name){
        return (isset($this->{$name}))
            ?$this->{$name}
            :null;
    }

}