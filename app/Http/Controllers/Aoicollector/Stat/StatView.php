<?php

namespace IAServer\Http\Controllers\Aoicollector\Stat;

use IAServer\Http\Controllers\Aoicollector\Model\Maquina;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\StatResume;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class StatView extends StatController
{
    public $debug;

    /**
     * Muestro estadisticas de la primer maquina de la lista
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $maquina = Maquina::orderBy('linea')->take(1)->first();
        return $this->indexWithFilter($maquina->id);
    }

    /**
     * Muestro estadisticas filtrada por maquina y/o programa
     *
     * @param $linea
     * @param string $programa
     * @return \Illuminate\View\View
     */
    public function indexWithFilter($id_maquina, $turno="", $fecha="", $resume_type="", $programa="", $op="")
    {
        // Crea una session con filtros
        if(!$turno)
        {
            $turno = Filter::turnoSession();
        }

        $carbonDate = Util::dateRangeFilterEs('inspection_date_session');
        $fecha = $carbonDate->desde->format('d-m-Y');

        // Cambio el formato de la fecha
        $fecha_eng = Util::dateToEn($fecha);
        $maquina = Maquina::find($id_maquina);

        $programas = PanelHistory::programUsedByLine($maquina->linea, $fecha_eng, $turno);

        if($programa) {
            $resume = $this->aoiResume($maquina->id, $turno, $fecha_eng, $programa, $op, $resume_type );
            $reference = $this->referenceResume($maquina->id, $turno, $fecha_eng,$programa, $op, $resume_type);
        }

        $output = compact('maquina','fecha','fecha_eng','turno','programas','resume','reference', 'resume_type');

        return Response::multiple($output, 'aoicollector.stat.index');
    }

    public function resume()
    {
        // Crea una session para filtro de fecha
        Filter::dateSession();

        // Obtengo la fecha, y cambio el formato 16-09-2015 -> 2015-09-16
        $fecha = Util::dateToEn(Session::get('inspection_date_session'));

        $resume = StatResume::where('fecha',$fecha)
            ->where('turno','M')
            ->groupBy(['linea','programa','op'])
            ->orderBy(DB::raw(' CAST(SUBSTRING_INDEX(`linea`, \'-\', -1) as UNSIGNED)'))
            ->get();

        $resume = collect($resume)->groupBy('linea');
        $output = compact('resume');
        return Response::multiple($output,'aoicollector.stat.resume');
    }
}
