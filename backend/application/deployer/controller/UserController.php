<?php
namespace app\deployer\controller;
use think\Controller;

class UserController extends Controller
{
    use \app\deployer\traits\CURDControllerTrait;

    protected function model(){
        return model('User');
    }

    protected function validator(){
        return 'app\admin\validate\ProjectValidate';
    }

    protected function config(){
        return [
            'sortable' => ['id', 'name']
        ];
    }
}