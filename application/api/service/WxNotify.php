<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/31
 * Time: 14:29
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Exception;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data,&$msg)
    {
        if ($data['result_code'] == 'SUCCESS')
        {
            $orderNo = $data['out_trade_no'];
            try{
                $order = \app\api\model\Order::where('order_no','=',$orderNo)->find();
                if($order->status == 1)
                {
                    $service = new Order();
                    $stockStatus = $service->checkOrderStock($order->id);
                    if ($stockStatus['pass'])
                    {
                        $this->updateOrderStatus($order->id,true);
                        $this->reduceStock($stockStatus);
                    }else{
                        $this->updateOrderStatus($order->id.false);
                    }
                }
                return true;
            }catch (Exception $e)
            {
                Log::record($e);
                return false;
            }
        }
        else{
            return true;
        }
    }
    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus)
        {
            Product::where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['count']);
        }
    }
    private function updateOrderStatus($orderID,$success)
    {
        $status = $success?OrderStatusEnum::PAID:OrderStatusEnum::PAID_BUT_OUT_OD;
        \app\api\model\Order::where('order_id','=',$orderID)->update(['status'=>$status]);
    }
}