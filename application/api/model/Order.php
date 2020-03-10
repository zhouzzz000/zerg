<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/21
 * Time: 11:23
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','delete_time','update_time'];
    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value)
    {
        if (empty($value))
        {
            return null;
        }
        return json_decode($value);
    }
    public function getSnapAddressAttr($value)
    {
        if (empty($value))
        {
            return null;
        }
        return json_decode($value);
    }
    public static function getSummaryByUser($uid, $page = 1, $size = 15)
    {
        $pagingData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }

    public static function getSummary($page = 1, $size = 15)
    {
        $pagingData = self::order('create_time desc')->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }
}