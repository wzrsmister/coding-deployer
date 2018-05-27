<?php
namespace app\base\traits\controller;

use think\Model;

trait UpdateControllerTrait{

    protected function beforeUpdate(Model $model){}

    protected function afterUpdate(Model $model){}

    public function update($id = 0, $data = null){
        try {
            $data = $data === null ? input() : (array)$data;
            $model = $this->loadModel($id);
            $this->valid($data, 'update');
            $model->load($data, 'update');
            $this->beforeUpdate($model);
            $model->save();
            $this->afterUpdate($model);
            return $this->responseReturn(200, '修改成功', ['id' => $id]);
        } catch (\Exception $e) {
            return $this->responseError('修改失败', $e);
        }
    }
}