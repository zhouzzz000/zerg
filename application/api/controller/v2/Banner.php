<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/5/8
 * Time: 10:20
 */

namespace app\api\controller\v2;

use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\TestValidate;
use app\lib\exception\BannerMissException;
use think\Controller;


class Banner extends Controller
{
    public function getBanner($id)
    {

        return 'This is v2 version.';
    }
}