<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/31
 * Time: 19:53
 */

namespace app\api\model;


use think\Model;

class Image extends BaseModel
{
    protected $hidden = ['delete_time','update_time','id','from'];

                 /**
                  * 固定格式：get+属性名+Attr
                  * $value 为传进来的对用属性名的属性值，每次传一个
                  * $data  为传进来的对应该属性值的一条记录
                **/

    //
    public function getUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }
}