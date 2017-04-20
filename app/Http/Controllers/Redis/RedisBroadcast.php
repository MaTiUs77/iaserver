<?php
namespace IAServer\Http\Controllers\Redis;

class RedisBroadcast extends RedisController
{
    private $channel = null;
    public $result = null;
    public $exist = false;

    public function __construct($channel,$connectionName="default"){
        parent::__construct($connectionName);
        $this->channel = $channel;
    }

    public function put($newCache,$seg=60) {
        $msg = json_encode($newCache);
        $this->putAndExpire($this->channel,$msg,$seg);
        return $msg;
    }

    public function emit($newCache,$seg=60){
        // Guarda un nuevo dato en el key definido (el key seria el channel definido)
        $jsonMessage = $this->put($newCache,$seg);
        // Publica en el channel el dato enviado
        $this->publish($this->channel,$jsonMessage);
    }

    public function ttl() {
        return $this->redis->ttl($this->channel);
    }

    public function getCache() {
        $this->result = $this->cached($this->channel);

        if($this->result==null) {
            $this->exist = false;
        } else
        {
            $this->exist = true;
        }

        return $this->result;
    }
}
?>