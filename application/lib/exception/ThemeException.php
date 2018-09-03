<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/6/26
 * Time: 21:13
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '指定主题不存在，请检查主题ID';
    public $errorCode = 30000;
}