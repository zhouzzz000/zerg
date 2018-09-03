<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/8
 * Time: 17:40
 */

namespace app\api\validate;

class IDMustBePostiveInt extends BaseValidate
{
    protected $message = [
        'id' => 'id必须是正整数',
    ];
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'num' => 'in:1,2,3'
    ];

}