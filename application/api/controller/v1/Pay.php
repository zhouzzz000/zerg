<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/25
 * Time: 13:27
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];
    public function getPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new \app\api\service\Pay($id);
        return $pay->pay();
    }
    //微信支付回调的接收接口
    public function receiveNotify()
    {
        //通知频率为15/15/30/180/1800/1800/1800/3600 单位：秒

        //1.检查库存量，超卖
        //2.更新这个订单的status的状态
        //3.减库存
        //如果成功处理，我们返回微信成功处理的消息，否则，返回没有成功处理的消息

        //微信返回特点：post，xml格式，不会携带参数
    }
}