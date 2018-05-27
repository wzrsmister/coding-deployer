<?php
namespace app\base\model;
use think\Model;

class Base extends Model
{

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

}