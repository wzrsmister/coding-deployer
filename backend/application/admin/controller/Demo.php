<?php
namespace app\admin\controller;

use think\Model;
use app\base\traits\controller\CURDControllerTrait;

class Demo extends Base
{
    use CURDControllerTrait{
        CURDControllerTrait::index as list;
    }

    public function index(){
        return '请访问list方法';
    }

    protected function model()
    {
        return model('admin');
    }

    protected function validator(){
        return '\app\admin\validate\AdminValidate';
    }

    protected function formatObject($item, $key, $scene = ''){
        $item->createtime = date('Y-m-d H:i:s',$item->createtime);
    }

    protected function config(){
        return [
            'field'      => 'admin_id,admin_name,password,createtime,level',
            'noDisplay'  => ['password', 'level'],
            'searchable' => [
                'id'=>'admin_id',
                // 'level',
                'name' => ['admin_name', 'LIKE', 'options' => ['lLike' => false]],
                // ['level', '<>', 'defaultValue'=>2,'options' => ['logic' => 'or']]
            ],
            'sortable' => ['admin_id','level']
        ];
    }

    protected function beforeCreate(Model $model){
        /*$file = $_FILES['img'];
        $url = $this->storage($file);
        $model->url = $url;*/
        /*$data = [];
        $model->load($data, 'insert');*/
        $data = ['admin_name'=>'zhangsan','level'=>2,'createtime'=>'12345455'];
        $model->load($data,'insert');
    }

    protected function beforeUpdate(Model $model)
    {
        $data = ['admin_id'=>4123,'nickname'=>'xiaoyangj'];
        $model->load($data,'update');
    }

    public function compare()
    {
        //$this->valid(input(), 'insert');die;
        //$this->valid(input(), 'insert', new \app\admin\validate\ProjectValidate());
        //$this->valid(input(), 'insert', '\app\admin\validate\ProjectValidate');
        $data = model('admin')->field('admin_name,level,password')
            ->compare('admin_name','like','guanli',['lLike'=>false])
            ->compareOr('admin_id',30)
            ->compare('admin_id','<=','30')->select();
        dump(model('admin')->getLastSql());
        dump($data);
    }
}