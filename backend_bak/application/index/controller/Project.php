<?php
namespace app\index\controller;

class Project extends CURDController
{

    public function model(){
        return model('project');
    }

    protected function config(){
        return [
            'searchs' => [
                'v.id', 
                'name%',
                ['name', 'LIKE', 'options' => ['rLike' => false]], 
                ['status', 'IN'], 
                ['status', 'BETWEEN'], 
                function($query, $params){
                    $query->whereRaw("id!=:id", ['id'=>5]);
                }
            ],
            'defaultParam' => ['v_title' => 'test'],
            'paginate' => [],
        ];
    }

    public function index()
    {
        $model = model('project');
        //$data = $model->getList(['name' => ['like', '%test%']]);
        $data = $model
        //->where("status", "in", [1,2])
        ->compare("name", "like", input("name"))
        ->compare("id", input("id"))
        ->compare("status", "in", array_filter(explode(",", input("status"))))
        ->compare("status", "between", [0, 100])
        ->whereRaw("id=:id AND status=:status", ['id'=>1, 'status'=>2])
        ->select();

        print_r($model->fetchSql());

        $query = new \CQuery();
        $query->compare("name", "like", input("name"))
        ->compare("id", input("id"))
        ->compare("status", "in", array_filter(explode(",", input("status"))))
        ->compare("status", "between", [0, 100])
        ->whereRaw("id=:id AND status=:status", ['id'=>1, 'status'=>2]);


        db('projects')
        ->where($query)
        ->select();
        //print_r($data);
    }
}