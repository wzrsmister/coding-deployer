<?php
namespace app\admin\controller;

use app\admin\validate\ProjectValidate;
use app\base\controller\CURDController;
use think\Validate;
use think\Model;
use think\db\Query;

class Project extends CURDController
{

    protected $validator = 'app\admin\validate\ProjectValidate';

    public function model(){
        //return model('project')->with('group');
        //return model('project')->alias('p')->join('groups g', 'p.group_id = g.id');
        //return model('project')->alias('p');
        return model('project');
    }

    protected function config(){
        return [
            'field'      => '*',
            'noDisplay'  => ['private_key', 'public_key'],
            'searchable' => [
                'id', 
                'name%',
                'rep' => '%repository%', 
                'name' => ['name', 'LIKE', 'options' => ['rLike' => false]], 
                //'p.id', 
                //'g.id', 
                //'name' => 'p.name%',
                //'p.rep' => '%repository%', 
                //'gname' => ['g.name', 'LIKE', 'options' => ['rLike' => false]], 
                //['status', 'IN'], 
                //'status_1' => ['status', '>='],
                //'status_2' => ['status', '<', 'defaultValue' => 100],
                //['status', 'BETWEEN', 'defaultValue' => [0, 100]], 
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
        //$item->group;
        return $item;
    } 

    public function afterSearch(Query $query, $params = []){
        //$query->compare("id", ">=", input('id'));
    }

    protected function afterIndex(){
        //dump($this->lastSql());
    }
 
}