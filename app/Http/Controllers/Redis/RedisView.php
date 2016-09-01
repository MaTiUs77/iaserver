<?php
namespace IAServer\Http\Controllers\Redis;

use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\TrazaStocker;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\Trazabilidad\Sfcs\Sfcs;

class RedisView extends Controller
{
    public function index()
    {
        $barcode = 'STK03581';
        try
        {
            $stockerRedis = \LRedis::get($barcode);

            if($stockerRedis == null)
            {
                $trazaStocker = new TrazaStocker();
                $stocker = $trazaStocker->findStocker($barcode);
                \LRedis::set($stocker->stocker->barcode, json_encode($stocker));
                //\LRedis::publish('stocker.update', json_encode($stocker));
            } else
            {
                $stocker = json_decode($stockerRedis);
            }

        } catch(\Exception $e)
        {
            return response()->view('errors.exception', ['mensaje'=> "Error al ejecutar Redis"], 500);
        }



        dd($stocker);

        return $stocker;
    }
}
?>