<?php

use Dottwatson\CrudGenerator\Crud\Handlers\RelationHandler;

$uuid = uniqid('tg_');
?>
<div class="row mt-2">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target=".<?=$uuid?>container" aria-expanded="false" aria-controls="<?=$uuid?>"><?=$value->count()?></button>
            <strong><?=($options['label'] ?? '')?>:</strong>
        <?php else: ?>
            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target=".<?=$uuid?>container" aria-expanded="false" aria-controls="<?=$uuid?>"><?=$value->count()?></button>
        <?php endif ?>
        <hr>
        <div class="col-md-12">
            <div class="row collapse <?=$uuid?>container">
                <?php
                try{
                    $fieldFormConfig = $model->getRelationConfig($name);
                    if(is_array($fieldFormConfig['handler'])){
                        $handler = new \Dottwatson\CrudGenerator\Crud\Handlers\RelationHandler;
                        $handler->setUSerConfig($fieldFormConfig['handler']);
                    }
                    elseif(is_object($fieldFormConfig['handler'])){
                        $handler = $fieldFormConfig['handler'];
                        $handler->init();
                    }
                    else{
                        $handlerClass    = $fieldFormConfig['handler'];
                        $handler         = new $handlerClass;
                        $handler->init();
                    }
                    
                    $itemView        = $handler->getConfig('item.crud.sheet',"laravel-crud-generator::sheet.fields.relational-item");
                }
                catch(\Exception $e){
                    $itemView = $options['item'] ?? "laravel-crud-generator::sheet.fields.relational-item";
                }

                foreach($value as $item){
                    echo view($itemView,compact('item','model','name','options'))->render();
                }
                ?>            
            </div>
        </div>
    </div>
</div>