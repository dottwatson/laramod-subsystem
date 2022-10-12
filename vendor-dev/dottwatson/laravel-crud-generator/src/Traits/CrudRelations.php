<?php

namespace Dottwatson\CrudGenerator\Traits;

use Dottwatson\CrudGenerator\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait CrudRelations{


    /**
     * get all available related models
     *
     * @return array Pair att type=>att configuration
     */
    public function getAvailableRelations()
    {
        return [];
    }

    /**
     * get relations configuration or part of it with dot notation
     *
     * @param string $type
     * @param string|null $needle
     * @return string|array|null
     */
    public static function getRelationConfig(string $type,string $needle = null)
    {
        $availableRelations = (new static)->getAvailableRelations();

        if(!array_key_exists($type,$availableRelations)){
            throw new \Exception("{$type} is not a valid related model for ".static::class);
        }
        
        $configuration = (is_string($availableRelations[$type]))
            ?array_replace_recursive( 
                config('relations.default_configuration'),
                config('relations.configurations.'.$availableRelations[$type])
            )
            :$availableRelations[$type];

        return ($needle)
            ?Arr::get($configuration,$needle)
            :$configuration;
    }

    public function checkRequestRelations(Request $request)
    {
        $availableRelations = $this->getAvailableRelations();

        // dump($availableRelations);
        
        foreach($availableRelations as $relationName=>$relationConfig){
            if($request->input("relational.{$relationName}._tokenRelation")){
                $relationData = decrypt($request->input("relational.{$relationName}._tokenRelation"));

                if(is_array($relationData['config']['handler'])){
                    $handler = new \Dottwatson\CrudGenerator\Crud\Handlers\RelationHandler;
                    $handler->setUSerConfig($relationData['config']);
                }
                elseif(is_object($relationData['config']['handler'])){
                    $handler = $relationData['config']['handler'];
                    $handler->init();
                }
                else{
                    $handlerClass   = $relationData['config']['handler'];
                    $handler        = new $handlerClass;
                    $handler->init();
                }
                        
                // dd($handler,$relationData);

                $modelRelationMethod    = $relationData['config']['through'];
                $relationObject         = $this->{$modelRelationMethod}();
                $parentModel            = $relationObject->getParent();
                $relatedModel           = $relationObject->getRelated();
                $relatedPrimaryKeyName  = $relatedModel->getKeyName();              
                
                // dd($relatedModel,$relatedPrimaryKeyName,$relationObject,$relationData,$request->input("relational.{$relationName}"));

                $ids = $request->input("relational.{$relationName}.{$relatedPrimaryKeyName}",[]);

                if($ids){
                    $parentModel->where('reference_id',$relationData['model_id'])->whereNotIn('related_id',$ids)->delete();

                    $upserts = [];
                    foreach($ids as $relatedId){
                        $extrafields = [];
                        foreach($request->input("relational.{$relationName}.extrafields.{$relatedId}",[]) as $extraName=>$extraValue){
                            $extrafields[$extraName] = $extraValue;
                        }

                        if(!$extrafields){
                            $extrafields = null;
                        }

                        $upserts[] = [
                            'reference_id'=> $relationData['model_id'],
                            'related_id'  => $relatedId,
                            'extrafields' => json_encode($extrafields)
                        ];
                    }
                    $parentModel->upsert($upserts,['reference_id','related_id'],['extrafields']);
                }
                else{
                    $parentModel->where('reference_id',$relationData['model_id'])->delete();
                }
            }
        }
    }


    /**
     * create relations for single id
     *
     * @param string $type
     * @param integer $relatedId
     * @param array $extrafields
     * @return mixed
     */
    public function makeRelation(string $type,int $relatedId,array $extrafields = [])
    {
        $config = $this->getRelationConfig($type);
        if(!$config){
            return false;
        }

        if(is_array($config['handler'])){
            $handler = new \Dottwatson\CrudGenerator\Crud\Handlers\RelationHandler;
            $handler->setUSerConfig($config);
        }
        elseif(is_object($config['handler'])){
            $handler = $config['handler'];
            $handler->init();
        }
        else{
            $handlerClass   = $config['handler'];
            $handler        = new $handlerClass;
            $handler->init();
        }
                

        $modelRelationMethod    = $config['through'];
        $relationObject         = $this->{$modelRelationMethod}();
        $parentModel            = $relationObject->getParent();

        if(!$extrafields){
            $extrafields = null;
        }

        $upserts = [
            [
                'reference_id'=> $this->getKey(),
                'related_id'  => $relatedId,
                'extrafields' => json_encode($extrafields)
            ]
        ];

        return $parentModel->upsert($upserts,['reference_id','related_id'],['extrafields']);
    }


    /**
     * delete a single or a set or all relation of $type
     *
     * @param string $type
     * @param array|int|null $ids If null, all relations for this object will be removed
     * @return mixed
     */
    public function deleteRelations(string $type,$ids = null)
    {
        $config = $this->getRelationConfig($type);
        if(!$config){
            return false;
        }

        if(is_array($config['handler'])){
            $handler = new \Dottwatson\CrudGenerator\Crud\Handlers\RelationHandler;
            $handler->setUSerConfig($config);
        }
        elseif(is_object($config['handler'])){
            $handler = $config['handler'];
            $handler->init();
        }
        else{
            $handlerClass   = $config['handler'];
            $handler        = new $handlerClass;
            $handler->init();
        }

        $modelRelationMethod    = $config['through'];
        $relationObject         = $this->{$modelRelationMethod}();
        $parentModel            = $relationObject->getParent();


        if($ids === null){
            return $parentModel->where('reference_id',$this->getKey())->delete();
        }
        else{
            $ids = (!is_array($ids))?[$ids]:$ids;
            return $parentModel->whereIn('related_id',$ids)->delete();
        }


    }
}
