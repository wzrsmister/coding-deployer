<?php
namespace app\admin\model;

class Server extends Base
{
    use \app\base\traits\model\CURDModelTrait;

    protected $table = 'servers';

    protected function insertable(){
        return ['name'];
    }

    protected function updateable(){
        return $this->insertable();
    }
}