<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/9/28
 * Time: 17:58
 */

namespace app\api\behavior;


class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
        header('Access-Control-Allow-Headers:token,Origin,Content-Type,Accept');
        if (request()->isOptions())
        {
            exit();
        }
    }
}