<?php
namespace Dottwatson\CrudGenerator\Crud\Sheets;


use Dottwatson\CrudGenerator\Sheet\Sheet;
use Dottwatson\CrudGenerator\Crud\Sheets\AttachmentExtraFieldsSheetData;

class AttachmentExtraFieldsSheet extends Sheet{

    public function setModel($model){
        $this->model = new AttachmentExtraFieldsSheetData($model);

        return $this;
    }

}