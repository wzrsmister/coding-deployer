<?php
namespace app\admin\model;

class Group extends Base
{
    use \app\base\traits\model\CURDModelTrait;
    
    protected $table = 'groups';
}