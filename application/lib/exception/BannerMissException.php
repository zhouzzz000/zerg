<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/15
 * Time: 23:00
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Banner不存在';
    public $errorCode = 40000;
}