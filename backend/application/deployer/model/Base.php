<?php

namespace app\deployer\model;

use think\Model;

class Base extends Model
{
    use \app\base\traits\model\CURDModelTrait;

    public function getAttributes($name = null){
        if($name === null){
            return $this->attributes;
        }else if(isset($this->attributes[$name])){
            return $this->attributes[$name];
        }else{
            return "";
        }
    }
}