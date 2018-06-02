<?php
namespace app\deployer\validate;

class UserValidate extends Validate
{

    protected $rule = [
        'name' => 'require'
    ];

    protected $message  =  [
        'name.require' => '项目名称不能为空'
    ];

    protected $scene = [
        
    ];

}