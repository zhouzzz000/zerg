<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 20:18
 */

namespace app\api\validate;


use app\lib\exception\BaseException;

class TokenGet extends BaseValidate
{
    protected $rule=[
      'code' => 'require|isNotEmpty',
    ];
    protected $message=[
        'code' => '没有code不能获取token',
    ];
}