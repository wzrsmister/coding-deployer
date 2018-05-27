<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件 

//从一个数组得获得某下标的值，可以是多维数组，支持点语法
function getValue($array, $key, $default = null, $multi = true){
    if ($key instanceof Closure) {
        return $key($array, $default);
    }

    if (is_array($array) && array_key_exists($key, $array)) {
        return $array[$key];
    }

    if ($multi && ($pos = strrpos($key, '.')) !== false) {
        $array = getValue($array, substr($key, 0, $pos), $default);
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
function toJson(array $data)
{
    return json_encode($data);
}


function responseSuccess($message){
    return responseReturn(200, $message, []);
}

function responseError($message, \Exception $e = null){
    if(config('app_debug') && $e !== null && !isAjax()){
        throw $e;    
    }else{
        $message = config('app_debug') && $e !== null ? $message . $e->getMessage() : $message;
    }
    return responseReturn(300, $message, []);
}

function responseReturn($code, $message, $data = [])
{
    return jsonOutput([
        'code'      => $code, 
        'message'   => $message, 
        'timestamp' => time(),
        'data'      => $data,
    ]);
}

function jsonOutput($data)
{
    $callback = input(input('var_jsonp_handler', 'callback'));
    if($callback){
        jsonp($data)->send();
    }else{
        json($data)->send();
    }
    exit(0);
}


function isAjax() {  
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {  
        if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))  
            return true;  
    }   
    return false;  
}  

















