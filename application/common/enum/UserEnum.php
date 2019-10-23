<?php

namespace app\common\enum;

class UserEnum extends Base
{
    const PLATFORM = 1;
    const CUSTOMER = 2;
    const MAKER = 3;

    public static $map = [
        self::PLATFORM => '平台',
        self::CUSTOMER => '消费者',
        self::MAKER => '厂商',
    ];
}