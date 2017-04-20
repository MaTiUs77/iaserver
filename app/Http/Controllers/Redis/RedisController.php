<?php
namespace IAServer\Http\Controllers\Redis;

use IAServer\Events\RedisSend;
use IAServer\Http\Controllers\Controller;

class RedisController extends Controller
{
    public $redis;

    public function __construct($connectionName="default") {
        $this->redis = \LRedis::connection($connectionName);
    }

    public function putAndExpire($key,$message,$expireInSeg=60)
    {
        $this->redis->set($key,$message);
        $this->redis->expire($key,$expireInSeg);
    }

    public function cached($key)
    {
        try
        {
            $result = json_decode($this->redis->get($key));
            if($result==null) {
                return null;
            } else {
                return (object) $result;
            }

        } catch( \Exception $ex)
        {
            return (object) ['error' => $ex->getMessage()];
        }
    }

    public function publish($canal,$message)
    {
        $this->redis->publish($canal,$message);
    }
}
?>