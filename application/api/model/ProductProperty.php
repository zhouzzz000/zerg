<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/15
 * Time: 17:35
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = ['product_id','delete_time','id'];
}