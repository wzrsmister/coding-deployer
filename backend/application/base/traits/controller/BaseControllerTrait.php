<?php
namespace app\base\traits\controller;

use think\Controller;
use think\db\Query;
use think\Request;
use think\Validate;
use think\Model;

trait BaseControllerTrait{

    protected function query(Query $query = null){
        if($query !== null){
            $this->query = $query;  
        }else if($this->query === null){
            $this->query = new \CQuery();
        }
        return $this->query;
    }

}