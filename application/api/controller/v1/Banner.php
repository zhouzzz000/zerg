<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/8
 * Time: 10:20
 */

namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\TestValidate;
use app\lib\exception\BannerMissException;
use think\Controller;

//独立验证
//验证器

class Banner extends Controller
{
    /**
     * 获取指定id的Banner信息
     * @url /banner/id
     * @http GET
     * @param $id
     */
    public function getBanner($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);
//        $banner->hidden(['delete_time','update_time']);//TODO：想隐藏什么
//        $banner->visible(['items']);//TODO:想显示什么
        if (!$banner)
        {
            throw new BannerMissException();
        }
        return $banner;
    }
}