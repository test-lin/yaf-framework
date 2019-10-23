<?php

namespace app\service;

use Yaf\Registry as YafRegistry;

class Base
{
    protected $configs;

    public function __construct()
    {
        $this->configs = YafRegistry::get('config')->toArray();
    }
}
