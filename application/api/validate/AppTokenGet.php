<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/9/28
 * Time: 16:53
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
    protected $rule = [
      'ac' => 'require|isNotEmpty',
      'se' => 'require|isNotEmpty'
    ];
}