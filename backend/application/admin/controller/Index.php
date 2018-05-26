<?php
namespace app\admin\controller;

class Index extends Base
{
    public function rules(){
        return array(
            array('vid,vids,name', 'require', 'message'=>'字段 {{attribute}} 不能为空!', 'on'=>'insert'),
            array('vids', 'split', 'maxLength'=> 1000, 'message'=>'字段 {{attribute}} 必须英文逗号分隔，最大长度不能超过{{maxLength}}个分隔值!'),
            array('format', 'split', 'default'=>'mp4', 'allowValues'=>'mp4,m3u8,flv', 'message'=>'字段 {{attribute}} 必须在允许值 {{allowValues}} 内!'),
            array('definition,order', 'split', 'default'=>'1300,1000,yuanhua,350', 'allowValues'=>'1300,1000,yuanhua,350', 'message'=>'字段 {{attribute}} 必须在允许值 {{allowValues}} 内!'),
            array('partner,domain', 'safe'),
        );
    }

    public function index()
    {
        return 'hello world';
    }


    public function test()
    {
        $param = $this->validater(input(), ["vid", "vids", "format"]);
    }
}
