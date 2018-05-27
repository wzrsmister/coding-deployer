<?php
namespace app\base\validate;

use think\Validate;

class BaseValidate extends Validate
{
    protected $commonRule = [
        'page' =>  'number|min:1',
        'pagesize'  =>  'number|between:1,1000',
        'id'  =>  'number',
    ];

     protected $commonMessage  =   [
        'id.number'       => 'id必须是数字',
        'page.number'       => 'page必须是数字',
        'page.min'          => 'page不能小于0',
        'pagesize.number'   => 'pagesize必须是数字',
        'pagesize.between'  => 'pagesize只能在1-1000之间', 
    ];
    
    protected $scene = [
        'search'  =>  ['page','pagesize'],
        'insert'  =>  [null],
        'update'  =>  [null],
        'delete'  =>  ['id'],
    ];

    public function mergeRule(){
        return array_merge((array)$this->commonRule, (array)$this->rule);
    }

    public function mergeMessage(){
        return array_merge((array)$this->commonMessage, (array)$this->message);
    }

}