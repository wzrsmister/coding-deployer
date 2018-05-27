<?php
namespace app\base\traits\controller;

use think\Model;

trait CreateControllerTrait{

    protected function beforeCreate(Model $model){}

    protected function afterCreate(Model $model){}

    public function create($data = null){
        try {
            $data = $data === null ? input() : (array)$data;
            $model = $this->model();
            $this->valid($data, 'insert');
            $model->load($data, 'insert');
            $this->beforeCreate($model);
            if($model->save()){
                $this->afterCreate($model);
                $this->responseSuccess('添加成功');
            }else{
                throw new \Exception();  
            }
        } catch (\Exception $e) {
            $this->responseError('添加失败', $e);
        }
    }

}