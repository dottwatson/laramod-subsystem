<?php
namespace Dottwatson\DatatableGenerator\Yajra\DataTables;

use Yajra\DataTables\EloquentDataTable as YajraEloquentDataTable;
use Illuminate\Database\Query\Expression;



class EloquentDataTable extends YajraEloquentDataTable{


    /**
     * Patch for fix about ambiguous field and meta fields.
     * Ambiguous field error will appear when query use join table and search with keyword.
     *
     * @param  mixed  $query
     * @param  string  $column
     * @return string
     */
    protected function addTablePrefix($query, $column)
    {
        //check if a model with meta.
        //If it is, check if the column is a meta column
        //and prevent the table name to be prepended on column
        $model = $query->getModel();
        if($model && method_exists($model,'isMeta')){
            if($model::isMeta($column)){
                $column = $model::queryMeta($column);
                return $this->wrap($column);
            }
        }
        
        if (strpos($column, '.') === false) {
            $q = $this->getBaseQueryBuilder($query);
            if (! $q->from instanceof Expression) {
                $column = $q->from . '.' . $column;
            }
        }

        return $this->wrap($column);
    }



}