<?php

namespace IAServer\Http\Controllers\Aoicollector\Pizarra;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Pizarra\PizarraCone\ProduccionCone;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class PizarraView extends Controller
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
        return redirect(route('aoicollector.pizarra.linea',1));
    }

    public function removeFilterGeneral()
    {
        $filter = array();
        Session::set('filterGeneral', $filter);
        return self::indexGeneral();
    }

    public function filterGeneral()
    {
        $filter = Input::all();
        Session::set('filterGeneral', $filter);
        return self::indexGeneral();
    }

    public function indexGeneral()
    {
        dd("La pizarra de produccion se encuentra en mantenimiento, intente mas tarde...");


        $pizarraFilterGeneral = Filter::makeSession('filterGeneral');

        // Obtengo lineas de produccion
        $produccion = Produccion::vista()
            ->groupBy('linea')
            ->whereNotNull('id_maquina')
            ->orderBy('numero_linea');

        if($pizarraFilterGeneral=='') {
            $pizarraFilterGeneral = array();
        }

        if(count($pizarraFilterGeneral)>0)
        {
            foreach ($pizarraFilterGeneral as $numero_linea => $filter) {
                $produccion = $produccion->where('numero_linea','<>',$numero_linea);
            }
        }

        $produccion = $produccion->get();

/*
//            ->where('numero_linea',2)
//            ->limit(1)
            ->get();
*/

        $pizarra = array();

        foreach($produccion as $prod)
        {
            $resume = new PizarraResume($prod->numero_linea);
            $pizarra[] = $resume;
        }

        $output = compact('pizarra','pizarraFilterGeneral');

        return Response::multiple($output,'aoicollector.pizarra.general');
    }

    public function indexLinea($linea)
    {
        $resume = new PizarraResume($linea);
        $output = compact('resume');
        return Response::multiple($output,'aoicollector.pizarra.index');
    }

    /***
     * Obtiene lista de reportes de produccion de la fecha solicitada
     * calcula porcentaje de produccion por hora segun lo proyectado
     *
     * @param $linea
     * @param $dateEn
     * @return mixed
     */
    private function prepareProyectado($linea, $dateEn)
    {
        list($anio,$mes,$dia) = explode('-',$dateEn);

        $proyectado = ProduccionCone::where('linea', $linea)
            ->whereRaw("dia = '".$dia."'")
            ->whereRaw("mes = '".$mes."'")
            ->whereRaw("anio = '".$anio."'")
            ->whereNotNull('proyectado')
            ->orderBy('horario', 'asc')
            ->get();

        $output = ProduccionCone::prepareProyectado($proyectado,$this->config);

        return $output;
    }
}
