<?php
namespace app\index\controller;
use think\Controller;
use think\db\Query;
use think\Request;

abstract class CURDController extends Controller{

    protected $model;
    protected $controller;
    protected $action;
    protected $module;
    protected $query;
    protected $params = [];

    abstract public function model();

    public function query(Query $query = null){
        if($query !== null){
            $this->query = $query;  
        }else if($this->query == null){
            $this->query = new \CQuery();
        }
        return $this->query;
    }

    public function __construct(){
        parent::__construct();
        $this->init();
    }

    protected function init(){
        $this->module = strtolower($this->request->module());
        $this->controller = strtolower($this->request->controller());
        $this->action = strtolower($this->request->action());
    }

    protected function load($data = []){
        foreach ((array)$data as $name => $value) {
            $this->params[$name] = $value;
        }
        return $this->params;
    }

    protected function defaultConfig(){
        return [
            "searchs" => [],
            "sorts" => [],
            "field" => "*",
            "noDisplay" => [],
            "defaultSort" => $this->model()->getPk() . " DESC ",
            "defaultParam" => [],
            'paginate' => [],
        ];
    }

    protected function config(){
        return [];
    }

    protected function getConfig(){
        $config = $this->defaultConfig();
        foreach ($this->config() as $k => $v) {
            $config[$k] = isset($config[$k]) ? array_merge($config[$k], $v) : $v;
        }
        return $config;
    }

    protected function search($params, $searchs = []){
        $query = $this->query();
        foreach ($searchs as $search) {
            if(is_string($search)){
                $field = trim($search, "%");
                $options['lLike'] = substr(trim($search), 0, 1) == "%" ? true : false;
                $options['rLike'] = substr(trim($search), -1) == "%" ? true : false;
                $value = getValue($params, str_replace('.', '_', $field));
                $query->compare($field, array_sum($options) ? "LIKE" : "EQ", $value, $options);
            }else if(is_array($search)){
                list($field, $op, $value) = $search + [null, null, null];
                $options = isset($search['options']) ? $search['options'] : [];
                if($field === null) continue;
                if($value === null) $value = getValue($params, str_replace('.', '_', $field));;
                $query->compare($field, $op, $value, $options);
            }else if($search instanceof \Closure){
                $search($query, $params);
            }
        }
        return $query;
    }

    /*protected function filter(Request $request, $config){

        $this->load(array_merge($config['defaultParam'], $request->get()));
        $query = $this->search($this->params, $config['searchs']);
        print_r($this->params);die;
        $com->setSearchs($searchs);
        $com->setSortables($sorts);
        $this->sortable = $com->getSortables();
        $this->filterable = $com->getSearchs();
        $com->sort($com->sort);
        if($com->page <= 0){
            $com->page = 1;
        }
        if($com->size <= 0){
            $com->size = 10;
        }
        $this->com = $com;
        return $com;
    }*/

    public function list(Request $request){
        $model = $this->model();
        $config = $this->getConfig();
        $params = $this->load(array_merge($config['defaultParam'], $request->get()));
        $query = $this->search($params, $config['searchs']);
       
        $data = $model
            ->field($config['field'])
            ->where($query)
            ->paginate();

    print_r($model->fetchSql());die;
        print_r($query2);die;
        print_r($data);die;
    }

}

