<?php

namespace app\deployer\traits;

use app\base\traits\controller\CURDControllerTrait as CURD;

trait CURDControllerTrait{

    use CURD{
        CURD::index as indexAction;
        CURD::create as createAction;
        CURD::update as updateAction;
        CURD::delete as deleteAction;
    }

    protected function handleResultData($data){
        $headers = [];
        foreach ($this->model()->getAttributes() as $key => $value) {
            $headers[] = ['key' => $key, 'name' => $value];
        }
        $data['headers'] = $headers;
        return $data;
    }

}