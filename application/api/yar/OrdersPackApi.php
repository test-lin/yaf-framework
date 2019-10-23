<?php

namespace app\api\yar;

use Exception;

class OrdersPackApi extends Base
{
    /**
     * 订单包列表
     *
     * @param array $conditions
     * @param array $config
     * @return mixed
     * @throws Exception
     */
    public function list($conditions = [], $config = [])
    {
        return $this->initYar()->one('/server/v1/OrdersPack/list', [$conditions, $config]);
    }

    /**
     * 订单包改价
     *
     * @param int $ordersPackId 订单包ID
     * @param int $orderPrice 价格
     * @return mixed
     * @throws Exception
     */
    public function adjustPrice($ordersPackId, $orderPrice)
    {
        return $this->initYar()->one('/server/v1/OrdersPack/adjustPrice', [$ordersPackId, $orderPrice]);
    }
}