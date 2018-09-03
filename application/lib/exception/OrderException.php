<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/20
 * Time: 15:57
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80000;
}