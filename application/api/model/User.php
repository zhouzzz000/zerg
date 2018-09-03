<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 20:25
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address()
    {
        return $this->hasOne('UserAddress','user_id','id');
    }
    public static function getByOpenID($openid)
    {
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }

}