<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 19:31
 */

namespace app\api\model;


class Category extends BaseModel
{
    public function img()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }
}