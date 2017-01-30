<?php
namespace IAServer\Http\Controllers\Redis;

use IAServer\Events\RedisSend;
use IAServer\Http\Controllers\Controller;

class RedisController extends Controller
{
    public function push($canal,$message)
    {
        \LRedis::set($canal,$message);
    }
    public function cached($canal)
    {
        try
        {
            return (object) json_decode(\LRedis::get($canal));
        } catch( \Exception $ex)
        {
            return (object) ['rediserror' => $ex->getMessage()];
        }
    }

    public function publish($canal,$message)
    {
        return event(new RedisSend($canal,json_encode($message)));
    }
}
?>