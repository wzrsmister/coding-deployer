<?php
namespace app\admin\model;

class Server extends Base
{
    use \app\base\traits\model\CURDModelTrait;

    protected $table = 'servers';

    protected function insertAble(){
        return ['name'];
    }

    protected function updateAble(){
        return $this->insertable();
    }
}