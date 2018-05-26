<?php
namespace app\admin\model;
use think\Model;

class Base extends Model
{
    public function load($data, $scene = ''){
        $able = $scene == 'insert' ? $this->insertable() : $this->updateable();
        foreach ($able as $key => $field) {
            if(isset($data[$field]) && $data[$field] !== null){
                $this[$field] = $data[$field];
            }
        }
        return $this;
    }

    public function beforeSave($scene = ''){

    }

    public function afterSave($scene = ''){
        
    }
}