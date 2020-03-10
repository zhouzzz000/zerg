<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/9/28
 * Time: 17:06
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
        public static function check($ac, $se)
        {
                $app = self::where('app_id','=',$ac)
                           ->where('app_secret','=',$se)->find();
                return $app;
        }
}