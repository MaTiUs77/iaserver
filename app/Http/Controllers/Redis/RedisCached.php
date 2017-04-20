<?php
namespace IAServer\Http\Controllers\Redis;

use IAServer\Events\RedisSend;
use IAServer\Http\Controllers\Controller;

class RedisCached extends RedisController
{
    private $channel = null;
    public $result = null;
    public $exist = false;

    public function __construct($channel,$connectionName="default"){
        parent::__construct($connectionName);

        $this->channel = $channel;
        $this->result = $this->cached($channel);

        if($this->result==null) {
            $this->exist = false;
        } else
        {
            $this->exist = true;
        }
    }

    public function put($newCache,$seg=60) {
        $msg = json_encode($newCache);
        $this->putAndExpire($this->channel,$msg,$seg);

        return $msg;
    }

    public function broadcast($newCache,$seg=60){
        // Guarda un nuevo dato en el key definido (el key seria el channel definido)
        $jsonMessage = $this->put($newCache,$seg);
        // Publica en el channel el dato enviado
        $this->publish($this->channel,$jsonMessage);
    }

    public function ttl() {
        return $this->redis->ttl($this->channel);
    }
}
?>