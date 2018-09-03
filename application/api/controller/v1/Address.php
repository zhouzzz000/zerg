<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/16
 * Time: 13:59
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\User;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];

    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        // 根据token获取uid
        //根据uid查找用户数据，判断用户是否存在，如果不存在则抛出异常
        //获取用户从客户端提交来的地址信息
        //根据用户地址信息是否存在从而判断是增加地址还是更新地址
        $uid = \app\api\service\Token::getCurrentUid();
        $user = User::get($uid);
        if(!$user)
        {
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if(!$userAddress)
        {
            $user->address()->save($dataArray);
        }else{
            $user->address->save($dataArray);
        }
        return json(new SuccessMessage(),201);
    }
}