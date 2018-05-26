<?php
namespace app\admin\controller;
use think\Controller;

class Base extends Controller
{

    protected function responseReturn($code, $message, $data = []){
        return $this->jsonOutput(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    protected function jsonOutput($data){
        header('Content-type: application/x-javascript'); 
        $json  = toJson($data);
        $callback = input("callback");
        echo $callback ? htmlentities($callback)."({$json})": $json;
        exit(0);
    }
}