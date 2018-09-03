<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/2
 * Time: 22:35
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15'
    ];
}