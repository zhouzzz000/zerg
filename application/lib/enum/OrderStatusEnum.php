<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/29
 * Time: 9:59
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //未支付
    const UNPAID = 1;
    //已支付
    const PAID = 2;
    //已发货
    const DELIVERED = 3;
    //已支付，但是库存量不足
    const PAID_BUT_OUT_OD = 4;
}