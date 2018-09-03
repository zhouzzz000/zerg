<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 19:38
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '指定的类目不存在，请检查商品分类ID';
    public $errorCode = 50000;
}