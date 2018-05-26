<?php
namespace app\index\model;

class Project extends Base
{
    protected $table = 'projects';
    public function getList($search=[],$field='*')
    {

        /*return $this->field($field)->where("id=:id AND name LIKE :name", 
            [
                "name" => "%test%",
                "id" => 2,
            ])->select();*/
        /*return $this->field($field)->addCondition(
            "id=:id AND name LIKE :name", 
            [
                "name" => "%test%",
                "id" => 1,
            ]
        )->select();*/

        return $this->field($field)->compare("id", 1)->select();
    }
}