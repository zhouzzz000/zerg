<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/9/28
 * Time: 16:56
 */

namespace app\api\service;


use app\api\model\ThirdApp;
use app\lib\exception\TokenException;

class AppToken extends Token
{
    public function get($ac, $se)
    {
        $app = ThirdApp::check($ac, $se);
        if (!$app)
        {
            throw new TokenException([
                'mag' => '授权失败',
                'errorCode' => 10004
            ]);
        }else{
            $scope = $app->scope;
            $uid = $app->id;
            $values = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = $this->saveToCache($values);
            return $token;
        }
    }
}