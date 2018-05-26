<?php
namespace app\admin\model;

class Project extends Base
{
    protected $table = 'projects';
   
    protected function insertable(){
        return ['name' , 'ip_address', 'user'];
    }

    protected function updateable(){
        return $this->insertable();
    }

    public function beforeSave($scene = ''){
        $this->updated_at = date('Y-m-d H:i:s');
        if($scene == 'insert'){
            $this->created_at = date('Y-m-d H:i:s');
        }
    }
}