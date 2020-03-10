<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 22:12
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        //32个字符组成的一组随机字符串
        $randChars = getRandChar(32);
        //用三组随机字符串进行md5加密
        $timeStamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt = config('secure.token_salt');
        return md5($randChars.$timeStamp.$salt);
    }

    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars)
        {
            throw new TokenException();
        }else{
            if(!is_array($vars))
            {
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key,$vars)) {
                return $vars[$key];
            }else if (array_key_exists($key,$vars['wxResult']))
            {
                return $vars['wxResult'][$key];
            }
            else{
                throw new Exception('尝试获取的token变量并不存在');
            }
        }
    }
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * @return bool
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     * 用户和管理员都能访问的权限
     */
    public static function needPrimaryScope()
    {
        $scope = Token::getCurrentTokenVar('scope');
        if($scope) {
            if ($scope >= ScopeEnum::user) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    /**
     * @return bool
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     * 只有管理员能访问的权限
     */
    public static function needExclusiveTokenVar()
    {
        $scope = Token::getCurrentTokenVar('scope');
        if($scope) {
            if ($scope == ScopeEnum::user) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }
    public static function isValidOperate($checkedUID)
    {
        if(!$checkedUID)
        {
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if($currentOperateUID === $checkedUID)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public static function verifyToken($token){
        $exist = Cache::get($token);
        if ($exist){
            return true;
        }else{
            return false;
        }
    }

    public function saveToCache($cachedValue)
    {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        $request = Cache::set($key,$value,$expire_in);
        if(!$request)
        {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }
}