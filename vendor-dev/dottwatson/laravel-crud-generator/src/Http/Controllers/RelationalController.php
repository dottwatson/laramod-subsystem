<?php

namespace Dottwatson\CrudGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Dottwatson\CrudGenerator\Form\FormBuilderTrait;
use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\FormBuilder;


class RelationalController extends Controller{
    use FormBuilderTrait;

    protected $ownerModelName;
    protected $ownerModelId;
    protected $name;
    protected $config;

    public function __construct()
    {
        $this->readTokenRelation();
    }


    protected function readTokenRelation()
    {
        $request        = request();
        $relationInfo   = decrypt($request->input('_tokenRelation'));

        $this->ownerModelName   = $relationInfo['model_name'];
        $this->ownerModelId     = $relationInfo['model_id'];
        $this->name             = $relationInfo['name'];
        $this->config           = $relationInfo['config'];

    }


    /**
     * add an item or get current items associated to model
     *
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return Response
     */
    public function item(Request $request,FormBuilder $formBuilder)
    {
        $name               = $this->name;
        $model              = $this->getModelInstance();
        $relationMethod     = $this->getConfig('through');
        $relation           = $model->{$relationMethod}();
        $results            = [];

        if($request->input('relation_action') == 'add'){
            $rows                   = $request->input('rows');

            $relatedModel           = $relation->getRelated();
            $relatedModelKeyName    = (new $relatedModel)->getKeyName();

            foreach($rows as $row){
                $itemView   = $this->getConfig('item.view');
                $itemForm   = $this->getConfig('item.crud.form');
                $item       = $relatedModel::find($row[$relatedModelKeyName]);

                if(in_array($item->getKey(),$request->input('preset',[]))){
                    continue;
                }

                if($itemForm){

                    $extraFieldsForm = $formBuilder->create($itemForm)
                        ->fillData([])
                        ->setReferenceName($name)
                        ->setReferenceId($item->getKey());
                }


                $results[]=view(
                    $itemView,
                    [
                        'uuid'              => uniqid('itemu'),
                        'item'              => $item,
                        'name'              => $name,
                        'extraFieldsForm'   => $extraFieldsForm ?? null,
                        'config'            => $this->getConfig()
                    ])->render();
            }

            return $results;

        }
        elseif($request->input('relation_action') == 'list'){
            $items                  = $relation->get();

            foreach($items as $item){
                $itemView   = $this->getConfig('item.view');
                $itemForm   = $this->getConfig('item.crud.form');

                if($itemForm){
                    $relationParentModel = $relation
                        ->getParent()
                        ->where('reference_id',$model->getKey())
                        ->where('related_id',$item->getKey())
                        ->first();

                    $extraFieldsForm = $formBuilder->create($itemForm)
                        ->fillData($relationParentModel->extrafields)
                        ->setReferenceName($name)
                        ->setReferenceId($item->getKey());
                }

                $results[]  = view(
                    $itemView,
                    [
                        'uuid'              => uniqid('itemu'),
                        'item'              => $item,
                        'name'              => $name,
                        'extraFieldsForm'   => $extraFieldsForm ?? null,
                        'config'            => $this->getConfig()
                    ]
                )->render();
            }

            return $results;
        }
    }


    /**
     * returns model instance
     *
     * @return Illuminate\Database\Eloquent\Model;
     */
    protected function getModelInstance()
    {
        $modelName  = $this->ownerModelName;
        $model      = ($this->ownerModelId)
            ?$modelName::find($this->ownerModelId)
            :(new $modelName);
        
        return $model;
    }

    /**
     * get configuration params
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(string $key = null,$default = null)
    {
        if($key === null){
            return $this->config;
        }

        return Arr::get($this->config,$key,$default);
    }


}