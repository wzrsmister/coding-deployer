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


    protected function insertable(){ return []; }

    protected function updateable(){ return []; }

    protected function beforeSave($sequence = ''){}

    protected function afterSave($sequence = ''){}

    public function load(array $params, $sequence = ''){
        $data = [];
        $able = $sequence == 'insert' ? $this->insertable() : $this->updateable();
        foreach ($able as $key => $field) {
            if(isset($params[$field]) && $params[$field] !== null){
                $this->setAttr($field, $params[$field]);
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