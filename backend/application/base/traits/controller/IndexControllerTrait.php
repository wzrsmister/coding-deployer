<?php
namespace app\base\traits\controller;

use think\db\Query;
use app\base\core\CQuery;

trait IndexControllerTrait{

    protected function query(){
        return new CQuery();
    }

    protected function beforeSearch(Query $query, $searchs){

    }

    protected function afterSearch(Query $query, $params = []){

    }

    protected function search(array $params, $searchable = []){
        $query = $this->query();
        $newSearchs = $this->beforeSearch($query, $searchable);
        $searchable = $newSearchs !== null && is_array($newSearchs) ? $newSearchs : $searchable;
        foreach ($searchable as $key => $search) {
            if(is_string($search)){
                $field = trim($search, "% ");
                $key   = is_int($key) ? str_replace('.', '_', $field) : $key;
                $options['lLike'] = substr(trim($search), 0, 1) == "%" ? true : false;
                $options['rLike'] = substr(trim($search), -1) == "%" ? true : false;
                $value = $this->getValue($params, $key, '');
                $query->compare($field, array_sum($options) ? "LIKE" : "EQ", $value, $options);
            }else if(is_array($search)){
                list($field, $op, $value) = $search + [null, null, null];
                $key      = is_int($key) ? str_replace('.', '_', $field) : $key;
                $options  = isset($search['options']) ? (array)$search['options'] : [];
                if($field === null) continue;
                if($op    === null) $op = 'EQ';
                if($value === null) {
                    $value = $this->getValue($params, $key, '');
                    $value = empty($value) && isset($search['defaultValue']) ? $search['defaultValue'] : $value;
                }
                $query->compare($field, $op, $value, $options);
            }else if($search instanceof \Closure){
                $search($query, $params);
            }
        }

        $this->afterSearch($query, $params);
        return $query;
    }

    protected function sort(Query $query, $config = []){


        $sortable = isset($config['sortable']) ? $config['sortable'] : [];
        $defaultSort = isset($config['defaultSort']) ? $config['defaultSort'] : [];
        $setting = isset($config['sortSetting']) ? $config['sortSetting'] : [];

        $sort = trim(input($setting['varName']));
        $allowDefault = true;
        if(!empty($sort)){
            $sortArr = $setting['multi'] ? explode($setting['multiDelimiter'], $sort) : [$sort];
            foreach ($sortArr as $key => $value) {
                $item = explode($setting['delimiter'], $value);
                if(!isset($item[0])) continue;

                $raw = false;
                $field = $item[0];
                $direction = '';
                if(array_key_exists($item[0], $sortable)){
                    $raw = true;
                    $field = $sortable[$field];
                }

                if(!in_array($field, $sortable)) continue;

                if(isset($item[1]) && isset($setting['directionMap'][$item[1]])){
                    $direction = $setting['directionMap'][$item[1]];
                }

                if($raw){
                    $query->orderRaw($field . ($direction ? ' ' . $direction : ''));
                }else{
                    $query->order($field, $direction);
                }
                $allowDefault = false;
            }
        }
        if ($allowDefault && $defaultSort) {
            $query->order($defaultSort);
        }
    }

    protected function paginate($setting){
        if($setting === false) return $setting;
        $data['page'] = trim(input($setting['pageVarName'], $setting['defaultPage']));
        $data['pageSize'] = trim(input($setting['pageSizeVarName'], $setting['defaultPageSize']));
        return $data;
    }

    public function index(){
        try{
            $config = $this->getConfig();
            $params = $this->load(input());
            $this->valid($params, 'search');
            $query = $this->search($params, $config['searchable']);
            $this->sort($query, $config);

            $model = $this->model()->field($config['field'])->where($query);
            $this->beforeIndex($model);

            if(($paginate = $this->paginate($config['paginate'])) === false){
                $result = $model->select();
            }else{
                $result = $model->paginate($paginate['pageSize'], false, ['page' => $paginate['page']]);
            }
            $result->each(function($item, $key){
                $this->formatObject($item, $key, 'list');
            });
            $data = [];
            foreach ($result as $key => $item) {
                $data[$key] = $this->handleNoDisplay(
                    $this->formatArray($item->toArray(), $key, 'list')
                    , $config['noDisplay']);
            }
            $this->afterIndex();
            if($paginate){
                $res = [
                    'total'     => (int)$result->total(),
                    'page'      => (int)$result->currentPage(),
                    'pageSize'  => (int)$result->listRows(),
                    'pageTotal' => (int)$result->lastPage(),
                    'list'      => (array)$data,
                ];
            }else{
                $res = [
                    'total' => count($data),
                    'list'  => $data,
                ];
            }
            if(config('app_debug')){
                $res = ['lastSql' => $this->lastSql()] + $res;
            }
            return $this->responseReturn(200, '请求成功', $this->handleResultData($res));
        } catch (\Exception $e) {
            return $this->responseError('查询失败', $e);
        }
    }

    protected function beforeIndex(Query $query){}  
    protected function afterIndex(){}  
    protected function formatObject($item, $key, $scene = ''){}
    protected function formatArray($item, $key, $scene = ''){ return $item; }
    protected function handleResultData($data){ return $data; }
    protected function handleNoDisplay($item, $noDisplay = []){
        foreach ($noDisplay as $field) {
            if(isset($item[$field])){
                unset($item[$field]);
            }
        }
        return $item;
    }

}