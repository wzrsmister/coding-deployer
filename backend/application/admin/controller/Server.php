<?php
namespace app\admin\controller;

use app\admin\validate\ProjectValidate;
use app\base\controller\CURDController;
use think\Validate;
use think\Model;
use think\db\Query;

class Server
{
 
    use \app\base\traits\controller\ConfigControllerTrait;
    use \app\base\traits\controller\BaseControllerTrait;
    use \app\base\traits\controller\IndexControllerTrait;

    protected function model(){
        return model('server');
    }

    protected function validator(){
        return 'app\base\validate\BaseValidate';
    }

    protected function config(){
        return [
            'searchable' => ['id', '%name%', 'user', 'status'],
            'sortable'   => ['id', 'name', 'created_at'],
            'paginate'   => false,
        ];
    }

    protected function handleResultData($data){
        return ['channel' => '广州'] + $data;
    } 
}