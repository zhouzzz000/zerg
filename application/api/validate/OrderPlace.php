<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/20
 * Time: 12:18
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts',
    ];
    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger',
    ];
    protected function checkProducts($values)
    {
        if(!is_array($values)){
            throw new ParameterException([
                'msg' => '商品参数不正确',
            ]);
        }
        if(empty($values))
        {
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach ($values as $value)
        {
            $this->checkValue($value);
        }
        return true;
    }
    protected function checkValue($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result)
        {
            throw new ParameterException([
                'msg' => '商品参数列表错误',
            ]);
        }
    }
}