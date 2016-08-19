<?php
namespace IAServer\Http\Controllers\Redis;

use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\TrazaStocker;
use IAServer\Http\Controllers\Controller;

class RedisView extends Controller
{
    public function index()
    {
        $trazaStocker = new TrazaStocker();
        $stocker = $trazaStocker->findStocker("STK05820");

        $redis = \LRedis::connection();
        $redis->publish('stocker.update', json_encode($stocker));

        return $stocker;
    }
}
?>