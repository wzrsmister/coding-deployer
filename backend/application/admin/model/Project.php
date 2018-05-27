<?php
namespace app\admin\model;

use app\base\model\Base;

class Project extends Base
{
    protected $table = 'projects';
   
    protected function insertable(){
        return ['name' , 'ip_address', 'user'];
    }

    protected function updateable(){
        return $this->insertable();
    }

    public function beforeSave($sequence = ''){
        $this->updated_at = date('Y-m-d H:i:s');
        if($sequence == 'insert'){
            $this->created_at = date('Y-m-d H:i:s');
        }
    }

    public function group()
    {
        return $this->hasOne('Group', 'id', 'group_id');
    }
}