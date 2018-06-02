<?php
namespace app\deployer\model;

class User extends Base
{
    protected $attributes = [
        'id' => 'ID',
        'create_id' => '创建人ID',
        'name' => '名字',
        'email' => '邮箱',
        'status' => '状态',
        'created_at' => '创建时间',
        'login_at' => '最后登陆',
        'updated_at' => '最后更新',
        'deleted_at' => '删除时间',
        'remark' => '备注',
    ];
}