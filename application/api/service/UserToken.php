<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 20:26
 */

namespace app\api\service;

use app\api\model\User as UserModel;
use app\api\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Cache;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;
    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
        $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult))
        {
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail)
            {
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }
    }
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errcode'],
        ]);
    }
    private function grantToken($wxResult)
    {
        //TODO：拿到openid
        //TODO：数据库看一下，这个openid是否已经存在
        //TODO：如果存在，则不处理，如果不存在则新增一条记录
        //TODO：生成令牌，准备缓存数据，写入缓存
        //TODO：把令牌返回到客户端去
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user)
        {
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function prepareCachedValue($wxResult,$uid)
    {
        $cachedValue['wxResult'] = $wxResult;
        $cachedValue['uid'] = $uid;
        //scope = 16 代表App用户的权限数值
        //scope = 32 代表CMS(管理员)用户的权限数值
        $cachedValue['scope'] = ScopeEnum::user;
//        $cachedValue['scope'] = ScopeEnum::Super;
        return $cachedValue;
    }
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }
}