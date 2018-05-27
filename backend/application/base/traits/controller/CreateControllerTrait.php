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
                // $this->responseReturn(200, '添加成功', ['id' => $model->{$model->getPk()}]);
                return $this->responseReturn(200, '添加成功', $model);
            }else{
                throw new \Exception();  
            }
        } catch (\Exception $e) {
            return $this->responseError('添加失败', $e);
        }
    }

}