<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller
{
    protected function validater($params, $attributes = null, $scenario = null){
        $validater = new \Validater();;
        $validater->setRules($this->rules());
        $validater->validate($params, $attributes, $scenario);
        if($validater->hasError() === true){
            $this->notice(-1, $validater->getMessage());
        }
        return $validater;
    }

    //通知请求端
    public function notice($code=0, $message='', $result = array()){
        $data = array(
            'code' => $code,
            'message' => $message,
            'result' => $result,
        );
        $this->jsonOutput($data);
    }

    protected function jsonOutput($data){
        header('Content-type: application/x-javascript'); 
        $json  = json_encode($data);
        $callback = input("callback");
        echo $callback ? $callback."({$json})": $json;
        exit(0);
    }
}