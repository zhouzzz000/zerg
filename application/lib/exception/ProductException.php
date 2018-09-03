<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/2
 * Time: 22:47
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '指定商品不存在';
    public $errorCode = 20000;
}