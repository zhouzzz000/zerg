<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/19
 * Time: 16:08
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use think\Request;

class Order extends BaseController
{
    //用户在选择商品后，向API提交它选择商品的相关信息
    //API在接收到信息后，需要检查订单相关商品的库存量
    //有库存，则把订单数据存入数据库中 = 下单成功了，返回客户端消息，告诉客户端可以支付了
    //调用我们的支付接口，进行支付
    //需要再次进行库存量检测
    //服务器这边就可以调用微信的支付接口进行支付
    //微信会返回一个支付的结果（异步）
    //返回结果为成功也要进行库存量检测
    //根据微信支付结果，成功就进行库存量的扣除，失败，返回一个支付失败的结果

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser']
    ];

    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = \app\api\service\Token::getCurrentUid();
        $pagingOrders = \app\api\model\Order::getSummaryByUser($uid,$page,$size);
        if ($pagingOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }
        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    public function getSummary($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $pagingOrders = \app\api\model\Order::getSummary($page,$size);
        if ($pagingOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }
        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    public function getDetail($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $orderDetail = \app\api\model\Order::get($id);
        if (!$orderDetail)
        {
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }
    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = \app\api\service\Token::getCurrentUid();
        $order = new \app\api\service\Order();
        $status = $order->place($uid,$products);
        return $status;
    }

}