<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/17
 * Time: 16:15
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}