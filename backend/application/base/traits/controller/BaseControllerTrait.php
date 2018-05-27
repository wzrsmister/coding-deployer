<?php
namespace app\base\traits\controller;

use think\Validate;
use think\Model;
use app\base\core\CQuery;

trait BaseControllerTrait{

    abstract protected function model();
    abstract protected function validator();

    protected function beforeLoad($data){
        return $data;
    }

    protected function afterLoad($data){
        return $data;
    }

    protected function load($data = []){
        $params = [];
        foreach ((array)$this->beforeLoad($data) as $name => $value) {
            $params[$name] = $value;
        }
        return $this->afterLoad($params);
    }

    protected function loadModel($id){
        $model = $this->model()->get($id);
        if($model===null){
            $this->responseError('记录不存在');
        }   
        return $model;
    }

    protected function beforeValidator(Validate $validator, $scene = ''){}

    protected function valid($params, $scene = ''){
        if(!($validator = $this->validator())){
            return true;
        }
        $validator = app()->validate($validator);
        $rule = $message = [];
        if(method_exists($validator, 'getCommonRule')){
            $rule = $validator->getCommonRule();
        }
        if(method_exists($validator, 'getCommonMessage')){
            $message = $validator->getCommonMessage();
        }
        $validator->batch(true);
        $validator->rule($rule, $message);
        $this->beforeValidator($validator, $scene);
        if (!$validator->check($params, [], $scene)) {
            $this->responseReturn(300, '参数错误', $validator->getError());
        }
    }


    //从一个数组得获得某下标的值，可以是多维数组，支持点语法
    protected function getValue($array, $key, $default = null, $multi = true){
        if ($key instanceof Closure) {
            return $key($array, $default);
        }

        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }

        if ($multi && ($pos = strrpos($key, '.')) !== false) {
            $array = $this->getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            return $array->$key;
        } elseif (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }

    // 将数组转化成json格式
    protected function toJson(array $data)
    {
        return json_encode($data);
    }


    protected function responseSuccess($message){
        return $this->responseReturn(200, $message, []);
    }

    protected function responseError($message, \Exception $e = null){
        if(config('app_debug') && $e !== null && !isAjax()){
            throw $e;    
        }else{
            $message = config('app_debug') && $e !== null ? $message . $e->getMessage() : $message;
        }
        return $this->responseReturn(300, $message, []);
    }

    protected function responseReturn($code, $message, $data = [])
    {
        return $this->jsonOutput([
            'code'      => $code, 
            'message'   => $message, 
            'timestamp' => time(),
            'data'      => $data,
        ]);
    }

    protected function jsonOutput($data)
    {
        //header('Content-type: application/x-javascript'); 
        $json  = $this->toJson($data);
        $callback = input("callback");
        echo $callback ? htmlentities($callback)."({$json})": $json;
        exit(0);
    }

    protected function isAjax() {  
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {  
            if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))  
                return true;  
        }   
        return false;  
    }  

    protected function lastSql(){
        return $this->model()->getLastSql();
    }
}