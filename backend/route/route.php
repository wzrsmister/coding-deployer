<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

Route::group('deployer', function(){
    Route::rule('/:controller/:action', 'deployer/:controllerController/:actionAction');
    /*Route::get(':controller/:id', 'blog/read');
    Route::post(':controller/:id', 'blog/update');
    Route::delete(':controller/:id', 'blog/delete');*/
});

return [
    
];
