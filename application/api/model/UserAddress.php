<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/17
 * Time: 22:07
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['id','delete_time','user_id'];
}