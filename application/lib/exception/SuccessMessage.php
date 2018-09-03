<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/17
 * Time: 16:33
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}