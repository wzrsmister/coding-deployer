<?php
namespace app\admin\controller;

use app\admin\validate\ProjectValidate;
use think\Validate;
use think\Model;
use think\db\Query;

class Project extends CURDController
{

    protected $validator = 'app\admin\validate\ProjectValidate';

    public function model(){
        return model('project');
    }

    protected function config(){
        return [
            'field'      => '*',
            'noDisplay'  => ['private_key', 'public_key'],
            'searchable' => [
                'id', 
                'name%',
                ['name', 'LIKE', 'options' => ['rLike' => false]], 
                ['status', 'IN'], 
                ['status', 'BETWEEN', 'defaultValue' => [0, 100]], 
                function($query, $params){
                    //$query->where('id', '<>', 5);
                    //$query->whereRaw("id!=:id2", ['id2'=>100]);
                }
            ],
            'defaultParam' => [],
            'sortable' => ['id', 'name', 'status'=>'abs(`status`)'],
            'paginate' => [],
        ];
    }  

    protected function formatObject($item, $key, $scene = ''){
        $item->status = $item->status == '1' ? '正常' : '异常';
        return $item;
    } 

    public function afterSearch(Query $query, $params = []){
        //$query->compare("id", ">=", input('id'));
    }

    protected function afterIndex(){
        //dump($this->lastSql());
    }
 
}