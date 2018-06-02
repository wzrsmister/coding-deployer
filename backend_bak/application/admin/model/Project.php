<?php
namespace app\admin\model;

class Project extends Base
{
    use \app\base\traits\model\CURDModelTrait;

    protected $table = 'projects';
   
    protected function insertAble(){
        return ['name' , 'ip_address', 'user'];
    }

    protected function updateAble(){
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