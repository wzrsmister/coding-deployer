<?php
namespace app\base\traits\model;

trait CURDModelTrait{

    public function getQuery(){
        return '\app\base\core\CQuery';
    }

    public static function init()
    {
        self::event('before_insert', function ($model) {
            $model->beforeSave('insert');
        });
        self::event('after_insert', function ($model) {
            $model->afterSave('insert');
        });
        self::event('before_update', function ($model) {
            $model->beforeSave('update');
        });
        self::event('after_update', function ($model) {
            $model->afterSave('update');
        });
    }


    protected function insertAble(){ return null; }

    protected function insertExcept(){ return []; }

    protected function updateAble(){ return null; }
    
    protected function updateExcept(){ return [$this->getPk()]; }

    protected function beforeSave($sequence = ''){}

    protected function afterSave($sequence = ''){}

    public function load(array $params, $sequence = 'update'){
        $data = [];
        $able = $sequence == 'insert' ? $this->insertAble() : $this->updateAble();
        $except = $sequence == 'insert' ? $this->insertExcept() : $this->updateExcept();

        foreach ($params as $field => $value) {
            if(($able === null || in_array($field, $able)) && !in_array($field, $except)){
                $this->setAttr($field, $value);
            }
        }

        return $this;
    }

    public function db($useBaseQuery = true)
    {
        if($this->queryInstance === null){
            if($this->query === null){
                $this->query = $this->getQuery();
            }
            $this->queryInstance = parent::db($useBaseQuery);
        }
        return $this->queryInstance;
    }

}