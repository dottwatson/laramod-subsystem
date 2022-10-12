<?php 
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;
use Dottwatson\DatatableGenerator\Table;
use Illuminate\Support\Facades\Crypt;

class RelationalTable extends Field {

    protected $table;

    protected function getTemplate()
    {
        return 'laravel-crud-generator::form.fields.relational.relational-table';
    }

    /**
     * set field source table
     *
     * @param  string|Dottwatson\DatatableGenerator\Table $table
     * @return static
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        
        $form           = $this->getParent();
        $model          = $form->getModel();
        $_tokenRelation = encrypt($options+['name'=>$this->name]);

        $itemUrl        = $this->buildItemsUrl($options['config']['item'],'add');
        $itemsListUrl   = $this->buildItemsUrl($options['config']['item'],'list');


        // $itemUrl = ($options['config']['item']['url'])
        //     ?$options['config']['item']['url']
        //     :route($options['config']['item']['route']);
        
        // $itemsListUrl = ($options['config']['list']['url'])
        //     ?$options['config']['list']['url']
        //     :route($options['config']['list']['route']);

            
        $table  = $this->getTable()->setOption([
            '_tokenRelation'    => $_tokenRelation,
            'itemUrl'           => $itemUrl,
            'itemsUrl'          => $itemsListUrl,
            'initComplete'      => '#!!window.CrudGenerator.relations.table.items!!#' 
        ]);
    
        $limit  = $this->options['limit'] ?? 1000000;


        $data = [
            'form'              => $form,
            'table'             => $table,
            '_tokenRelation'    => $_tokenRelation,
            'name'              => $this->name,
            'options'           => $this->options,
            'value'             => $this->getValue([])
        ];

        return $this->formHelper->getView()->make(
            $this->getViewTemplate(),
            $data
        )->render();    
    }


    protected function buildItemsUrl(array $itemInfo,string $action = 'add')
    {
        $url = null;
        if($itemInfo['url']){
            $queryStr   = parse_url($itemInfo['url'],PHP_URL_QUERY);
            $url        = $itemInfo['url'].(($queryStr)?'&':'?').'relation_action='.$action;
        }
        elseif($itemInfo['route']){
            if(is_array($itemInfo['route'])){
                $routeName                      = $itemInfo['route'][0];
                $routeData                      = $itemInfo['route'][1] ?? [];
                $routeData['relation_action']   = $action;
                
                $url = route($routeName,$routeData);
            }
            else{
                $url = route($itemInfo['route'],['relation_action' => $action]);
            }
        }

        return $url;
    }

}
