<?php
namespace app\admin\validate;

class ProjectValidate extends CommonValidate
{
    protected $rule = [
        'name' => 'require'
    ];

    protected $message  =  [
        'name.require' => '项目名称不能为空'
    ];

    protected $scene = [
        'search'  =>  ['page','pagesize'],
        'insert'  =>  ['name'],
        'update'  =>  [null],
        'delete'  =>  ['id'],
    ];
}