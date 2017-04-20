<?php

namespace IAServer\Http\Controllers\Aoicollector\Pizarra;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Redis\RedisBroadcast;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

ini_set('memory_limit', '500M');

class PizarraGeneral extends Controller
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

    private function filteredProductionLines() {
        $filterGeneral = Filter::makeSession('filterGeneral');
        if($filterGeneral=='') {
            $filterGeneral = array();
        }

        // Obtengo lineas de produccion
        $produccion = Produccion::vista()
            ->groupBy('linea')
            ->whereNotNull('id_maquina')
            ->orderBy('numero_linea');

        // Excluyo lineas aplicadas en el filtro
        if(count($filterGeneral)>0) {
            foreach ($filterGeneral as $numero_linea => $filter) {
                $produccion = $produccion->where('numero_linea','<>',$numero_linea);
            }
        }

        // Obtengo todas las lineas excepto las filtadas
        $produccion = $produccion->get();

        return $produccion;
    }


    public function index() {
        $pizarra = array();

        $produccion = $this->filteredProductionLines();

        $range = Util::dateRangeFilterEs('pizarra_fecha_range');

        foreach($produccion as $prod) {
            // Se define el KEY que se va a usar en Redis
            $channel = ['pizarra','linea',$prod->numero_linea,$range->desde->toDateString(),$range->desde->toDateString()];
            $channel = join(':',$channel);
            $redis = new RedisBroadcast($channel);
            $redis->getCache();

            // Existe en Redis? muestro cache
            if($redis->exist) {
                $resume = $redis->result;
                if(isset($resume->smt)) {
                    $resume->smt = (array) $resume->smt;
                }
            } else {
                $resume = new PizarraResume($prod->numero_linea, $range->desde, $range->hasta);
                $redis->put($resume,60 * 5);
            }

            $pizarra[] = $resume;
            // Guardo en cache por 5 minutos
        }

        // Obtiene el ttl
        $ttl = $redis->ttl();

        $output = compact('pizarra','pizarraFilterGeneral','ttl');

        return Response::multiple($output,'aoicollector.pizarra.general');
    }

    public function renderFilter($removeFilter="")
    {
        $filter = Input::all();
        if(empty($removeFilter))
        {
            $filter = array();
        }
        Session::set('filterGeneral', $filter);
        return self::index();
    }

}
