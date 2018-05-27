<?php
namespace app\base\traits\controller;

use think\Model;

trait DeleteControllerTrait{

    protected function beforeDelete(Model $model){}

    protected function afterDelete(Model $model){}

    //删除记录
    public function delete($id = 0){
        try {
            $this->valid(['id' => $id], 'delete');
            $model = $this->loadModel($id);
            $this->beforeDelete($model);
            if($model->delete()){
                $this->afterDelete($model);
                return $this->responseReturn(200, '删除成功', ['id' => $id]);
            }else{
                throw new \Exception();
            }
        } catch (\Exception $e) {
            return $this->responseError('删除失败', $e);
        }
    }
}