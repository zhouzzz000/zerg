<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 20:15
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;

class Token
{
    public function getToken($code='')
    {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        return ['token'=>$token];
    }

    public function getAppToken($ac = '', $se = '')
    {
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        header('Access-Control-Allow-Origin','*');

        return ['token' => $token];
    }

    public function verifyToken($token = '')
    {
        if (!$token){
            throw new ParameterException([
                'token不允许为空',
            ]);
        }
        $valid = \app\api\service\Token::verifyToken($token);
        return [
          'isValid' => $valid
        ];
    }
}