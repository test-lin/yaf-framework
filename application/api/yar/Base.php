<?php

namespace app\api\yar;

use Yaf\Registry as YafRegistry;
use RpcClient;

class Base
{
    /**
     * @var RpcClient
     */
    protected $yar;

    protected $token;

    public function __construct(array $token)
    {
        $this->token = $token;
    }

    protected function initYar()
    {
        if(!isset($this->yar)) {
            $configs = $this->token['config'];

            $this->yar = new RpcClient($configs['host'].$this->token['platform_id'].'_'.$this->token['user_id']);
        }

        return $this->yar;
    }
}