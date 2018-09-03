<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/15
 * Time: 22:57
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    //HTTP 状态码
    public $code = 400;
    //错误具体信息
    public $msg = 'params error';
    //自定义错误码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if(!is_array($params))
        {
            return;
        }
        if (array_key_exists('code',$params))
        {
            $this->code = $params['code'];
        }
        if (array_key_exists('errorCode',$params))
        {
            $this->errorCode = $params['errorCode'];
        }
        if (array_key_exists('msg',$params))
        {
            $this->msg = $params['msg'];
        }
    }
}