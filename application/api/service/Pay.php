<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/25
 * Time: 15:19
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;

//TODO:extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;
    function __construct($orderID)
    {
        if(!$orderID)
        {
            throw new Exception('订单号不允许为空');
        }
        $this->orderID = $orderID;
    }
    public function pay()
    {
        //订单号可能根本不存在
        //订单号存在，但是订单号和当前用户不匹配
        //订单有可能已经被支付过
        //进行库存量检测
        $this->checkOrderValid();
        $orderService = new Order();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass'])
        {
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);
    }
    private function makeWxPreOrder($totalPrice)
    {
        //openid
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid)
        {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url('http://qq.com');
        $wxConfig = new \WxPayConfig();
        return $this->getPaySignature($wxConfig,$wxOrderData);
    }
    private function getPaySignature($wxConfig,$wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxConfig,$wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' ||
            $wxOrder['result_code'] != 'SUCCESS')
        {
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }
    private function recordPreOrder($wxOrder)
    {
        \app\api\model\Order::where('id','=',$this->orderID)
            ->updata(['prepay_id'=>$wxOrder['prepay_id']]);
    }
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();

        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;

        unset($rawValues['appId']);

        return $rawValues;
    }
    private function checkOrderValid()
    {
        $order = \app\api\model\Order::where('id','=',$this->orderID)
                ->find();
        if(!$order)
        {
            throw new OrderException();
        }
        if(!Token::isValidOperate($order->user_id))
        {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID)
        {
            throw new OrderException([
                'msg' => '订单已经被支付的',
                'errorCode' => 80003,
                'code' => 400,
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

}