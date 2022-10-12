<?php 
namespace Dottwatson\DatatableGenerator;


class ModelTable extends Table{

    protected $model;

    protected $columns = [];
    protected $endpoint;

    protected $options;


    public function setModel(string $modelClass)
    {
        $this->model        = new $modelClass;
        $this->queryBuilder = $this->model->query();

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }
}


?>