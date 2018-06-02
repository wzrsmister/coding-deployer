<?php

namespace app\base\traits\controller;


trait CURDControllerTrait{
    use \app\base\traits\controller\ConfigControllerTrait;
    use \app\base\traits\controller\BaseControllerTrait;
    use \app\base\traits\controller\IndexControllerTrait;
    use \app\base\traits\controller\CreateControllerTrait;
    use \app\base\traits\controller\UpdateControllerTrait;
    use \app\base\traits\controller\DeleteControllerTrait;
}