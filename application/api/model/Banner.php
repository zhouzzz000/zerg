<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/12
 * Time: 21:30
 */

namespace app\api\model;


class Banner extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
                            //关联模型名，外键，当前模型的主键
    }
    public static function getBannerByID($id)
    {
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
    }
}