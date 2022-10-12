<?php
namespace Dottwatson\CrudGenerator\Models;
use Illuminate\Database\Eloquent\Model;

class RelationalModel extends Model{
    protected $relatedModel = null;


    protected $casts    = ['extrafields'=>'json'];


    public function getReferenced()
    {
        if($this->relatedModel){
            $modelName = $this->relatedModel;
            return $modelName::find($this->related_id);
        }
    }

    public function getReferencedKeyName()
    {
        $modelName = $this->relatedModel;
        return (new $modelName)->getKeyName();
    }

    public static function getByReferencedId(int $id)
    {
        return self::where('reference_id','id')->get();
    }

    public static function getByReferenced(Model $model)
    {
        $key = $model->getKeyName();

        return static::getByReferencedId($model->{$key});
    }

}