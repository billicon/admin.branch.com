<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-01-28
 * Time: 15:03
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //待支付
    const UNPAID = 1;
    //已支付
    const PAID   = 2;
    //已发货
    const DELIVERED = 3;
    //已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;
}