<?php
namespace app\base\traits\controller;

use app\base\core\CQuery;

trait ConfigControllerTrait{

    protected function defaultConfig($key = null, $default = []){

        $model = $this->model();
        if($model instanceof CQuery){
            $alias = $model->getAlias();
        }else{
            $alias = $model->getTable();
        }

        $config = [
            'searchable'   => [],
            'sortable'     => [],
            'sortSetting'  => [
                'varName'        => config('curd.sort.varName', 'sort'),
                'multi'          => config('curd.sort.multi', true),
                'multiDelimiter' => config('curd.sort.delimiter', ','),
                'delimiter'      => config('curd.sort.delimiter', '.'),
                'directionMap'   => config('curd.sort.valueMap', ['desc' => 'DESC', 'asc' => 'ASC']),
            ], 
            'field'        => "*",
            'noDisplay'    => [],
            'defaultSort'  => [$alias.'.'.$model->getPk() => "DESC"],
            'defaultParam' => [],
            'paginate'     => [
                'defaultPage'     => config('curd.paginate.defaultPage', 1),
                'defaultPageSize' => config('curd.paginate.defaultPageSize', 20),
                'pageVarName'     => config('curd.paginate.pageVarName', 'page'),
                'pageSizeVarName' => config('curd.paginate.pageSizeVarName', 'pagesize'),
            ],
        ];
        if($key === null){
            return $config;
        }else if(isset($config[$key])){
            return $config[$key];
        }else{
            return $default;
        }
    }

    protected function config(){
        return [];
    }

    protected function beforeConfig($data){
        return $data;
    }

    protected function getConfig(){
        $config = $this->beforeConfig($this->defaultConfig());
        foreach ($this->config() as $k => $v) {
            if(isset($config[$k]) && is_array($config[$k]) && is_array($v)){
                $config[$k] = array_merge($config[$k], $v);
            }else{
                $config[$k] = $v;
            }
        }
        return $this->afterConfig($config);
    }

    protected function afterConfig($data){
        return $data;
    }

}