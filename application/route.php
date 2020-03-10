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
use think\Route;
use think\Validate;
//Route::rule::('路由表达式','路由地址','请求地址','路由参数（数组）','变量规则（数组）')
//Route::rule('hello/:id','sample/test/login','GET|POST');//只允许GET和Post请求;
//TODO:  变量格式： :变量名
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
//Route::get('api/v2/banner/:id','api/v2.Banner/getBanner');
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

//Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
//Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
//TODO:路由分组
Route::group('api/:version/product',function (){
    Route::get('/by_category','api/:version.Product/getAllInCategory');
    Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
    Route::get('/recent','api/:version.Product/getRecent');
});

Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');
Route::post('api/:version/token/app','api/:version.Token/getAppToken');

Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address','api/:version.Address/getAddress');
//Route::get('api/:version/second','api/:version.Address/second');

Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/paginate','api/:version.Order/getSummary');
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);

Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');