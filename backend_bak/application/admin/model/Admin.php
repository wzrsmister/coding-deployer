<?php
namespace app\admin\model;

class Admin extends Base
{
    protected $table = 'admin_user';
    protected $pk = 'admin_id';
    use \app\base\traits\model\CURDModelTrait;

    protected function insertAble(){ 
        return null; 
    }

    protected function insertExcept(){ 
        return ['level']; 
    }

    public function beforeSave($sequence = ''){
        if($sequence == 'insert'){
            $this->createtime = $this->createtime ? : time();
        }
    }
}