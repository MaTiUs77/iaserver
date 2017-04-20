<?php

namespace IAServer\Http\Controllers\Aoicollector\Pizarra;

use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Redis\RedisBroadcast;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

ini_set('memory_limit', '500M');

class PizarraByLine extends Controller
{
    public $config = [
        'M' => [
            'desde' => 3,
            'hasta' => 15
        ],
        'T' => [
            'desde' => 15,
            'hasta' => 3
        ],
    ];

    public function index()
    {
        return $this->indexLinea(1);
    }

    public function indexLinea($linea)
    {
        $range = Util::dateRangeFilterEs('pizarra_fecha_range');

        // Se define el KEY que se va a usar en Redis
        $channel = ['pizarra','linea',$linea,$range->desde->toDateString(),$range->desde->toDateString()];
        $channel = join(':',$channel);

        $resume = new PizarraResume($linea,$range->desde,$range->hasta);

        // Guarda cache por 5 minutos
        $redis = new RedisBroadcast($channel);
        $redis->put($resume,60 * 5);

        $output = compact('resume');
        return Response::multiple($output,'aoicollector.pizarra.index');
    }
}
