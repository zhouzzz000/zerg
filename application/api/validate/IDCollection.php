<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/6/26
 * Time: 20:29
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
      'ids' => 'require|checkIDs',
    ];
    protected $message = [
      'ids' => 'ids参数必须是以逗号分隔的正整数',
    ];
    protected function checkIDs($values)
    {
        $values = explode(',',$values);
        if(empty($values))
        {
            return false;
        }
        foreach ($values as $id)
        {
            if(!$this->isPositiveInteger($id))
            {
                return false;
            }
        }
        return true;
    }
}