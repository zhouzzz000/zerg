<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/6/26
 * Time: 19:44
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = [
        'delete_time','main_img_id','pivot','from',
        'create_time','update_time','category_id',
    ];
    public function getMainImgUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }
    public static function getMostRecent($count)
    {
        $products = self::limit($count)->order('create_time desc')
            ->select();
        return $products;
    }
    public static function getProductsByCategoryID($categoryID)
    {
        $products = self::where('category_id','=',$categoryID)->select();
        return $products;
    }
    public function imgs()
    {
        return $this->hasMany('ProductImage','product_id','id')->order('order','asc');
    }
    public function properties()
    {
        return $this->hasMany('ProductProperty','product_id','id');
    }
    public static function getProductDetail($id)
    {
        $product = self::with(['imgs'])->with(['properties','imgs.imgUrl'])->find($id);
        return $product;
    }
}