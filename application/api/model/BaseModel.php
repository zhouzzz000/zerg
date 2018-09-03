<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/6/26
 * Time: 18:47
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    protected function prefixImgUrl($value,$date)
    {
        $finalUrl = $value;
        if($date['from'] == 1)
        {
            $finalUrl =  config('setting.img_prefix') . $value;
        }
        return $finalUrl;
    }
}