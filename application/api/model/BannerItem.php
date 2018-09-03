<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/22
 * Time: 20:27
 */

namespace app\api\model;


class BannerItem extends BaseModel
{
    protected $hidden = ['delete_time','update_time','img_id','id','banner_id'];
    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}