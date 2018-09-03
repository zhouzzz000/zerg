<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/19
 * Time: 12:51
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}