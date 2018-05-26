<?php
namespace app\admin\controller;

use app\admin\validate\ProjectValidate;
use think\Validate;
use think\Model;
use think\db\Query;

class Server extends CURDController
{

    protected $validator = 'app\admin\validate\CommonValidate';

    public function model(){
        return model('server');
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