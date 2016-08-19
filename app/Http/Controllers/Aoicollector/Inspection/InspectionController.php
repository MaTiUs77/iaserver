<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory;
use IAServer\Http\Controllers\Aoicollector\Model\DetalleHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Maquina;

use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class InspectionController extends Controller
{
    public function exportarLista($id_maquina,$fecha,$minOrMax)
    {
        $maquina = Maquina::findOrFail($id_maquina);
        $fecha = Util::dateToEn(Session::get('date_session'));

        $insp = PanelHistory::listar($id_maquina, $fecha, "",$minOrMax)->get();
        $filename = 'Stats_SMD-'.$maquina->linea.'_'.$fecha.'.csv';

        $csv = array();

        foreach ($insp as $item) {
            if(count($csv)==0) {
                $header = array_keys($item->toArray());
                unset($header[0]);
                unset($header[1]);
                unset($header[2]);
                unset($header[3]);
                unset($header[6]);
                unset($header[7]);
                unset($header[16]);
                unset($header[17]);
                unset($header[18]);

                $csv[] = $header;
            }

            $values = array_values($item->toArray());
            unset($values[0]);
            unset($values[1]);
            unset($values[2]);
            unset($values[3]);
            unset($values[6]);
            unset($values[7]);
            unset($values[16]);
            unset($values[17]);
            unset($values[18]);

            $csv[] = $values;
        }

        Util::convert_to_csv($csv,$filename,',',true,false);
    }

    /**
     * Muestra las inspecciones, por defecto muestra se muestra la primer maquina de la lista
     *
     * @return \Illuminate\View\View
     */
    public function listDefault()
    {
        $pagina = 1;
        $maquina = Maquina::orderBy('linea')->take(1)->first();
        return $this->listWithFilter($maquina->id,$pagina);
    }

    public function listWithOpFilter($op, $pagina=null)
    {
        $maquina = Maquina::orderBy('linea')->take(1)->first();
        return $this->listWithFilter($maquina->id,$pagina,$op);
    }

    /**
     * Muestra las inspecciones, filtradas por maquina
     *
     * @param $id_maquina
     * @param null $pagina
     * @return \Illuminate\View\View
     */
    public function listWithFilter($id_maquina,$pagina=null,$op='')
    {
        $insp = array();
        $por_pagina = 50;

        $maquina = Maquina::select('maquina.*','produccion.cogiscan')
        ->orderBy('maquina.linea')
        ->leftJoin('aoidata.produccion','produccion.id_maquina','=','maquina.id')
        ->where('maquina.id',$id_maquina)
        ->first();


        // Crea una session para filtro de fecha
        Filter::dateSession();

        // Por defecto la pagina a mostrar es la 1
        if(is_null($pagina)) { $pagina = 1; }

        // Obtengo la fecha, y cambio el formato 16-09-2015 -> 2015-09-16
        $fecha = Util::dateToEn(Session::get('date_session'));

        $total = PanelHistory::listar($id_maquina, $fecha, $op)->count();

        $programas = array();
        if(empty($op))
        {
            $programas = PanelHistory::programUsed($id_maquina, $fecha);
        }

        if(is_numeric($total)>0 )
        {
            $skip = ($pagina-1) * $por_pagina;
            $insp = PanelHistory::listar($id_maquina, $fecha, $op)->take($por_pagina)->skip($skip)->get();

            // Calcula paginas segun total y resultados a mostrar por pagina
            $paginas = ceil($total / $por_pagina);
        } else {
            $total = 0;
        }

        $maquinas = Maquina::select('maquina.*','produccion.cogiscan')
            ->orderBy('maquina.linea')
            ->leftJoin('aoidata.produccion','produccion.id_maquina','=','maquina.id')
            ->get();

        $output = compact('insp','maquinas','maquina','total','pagina','por_pagina','paginas','programas');

        return Response::multiple_output($output,'aoicollector.inspection.index');
    }

    /**
     * Muestra los bloques pertenecientes a un panel
     *
     * @param $id_panel
     * @return \Illuminate\View\View
     */
    public function listBlocks($id_panel)
    {
        //$bloques = BloqueController::listar($id_panel);
        $bloques = BloqueHistory::where('id_panel_history',$id_panel)->get();
        $output = compact('bloques');

        return Response::multiple_output($output,'aoicollector.inspection.partial.blocks');
    }

    /**
     * Muestra los detalles de inspeccion de un bloque
     *
     * @param $id_bloque
     * @return \Illuminate\View\View
     */
    public function listDetail($id_bloque)
    {
//        $detalle = DetalleController::listar($id_bloque);
        $detalle = DetalleHistory::fullDetail($id_bloque)->get();
        $output = compact('detalle');

        return Response::multiple_output($output,'aoicollector.inspection.partial.detail');
    }

    /**
     * Muestra los resultados de busqueda de un barcode
     *
     * @return \Illuminate\View\View
     */
    public function searchBarcode($search_barcode="")
    {
        $timeline = true;
        $barcode = Input::get('barcode');
        $maquina = null;
        $insp_by_date = array();
        $maquinas = Maquina::orderBy('linea')->get();

        if(!empty($search_barcode)) {
            $barcode = $search_barcode;
        }

        $findService = new FindInspection();
        $findService->withCogiscan = true;
        $findService->withSmt = true;
        $findService->withWip = true;
        $insp = (object) $findService->barcode($barcode);

        if(isset($insp->last))
        {
            if(isset($insp->last->panel->id_maquina)) {
                $maquina = Maquina::find($insp->last->panel->id_maquina);
            }

            if(isset($insp->historial))
            {
                $insp_by_date = collect($insp->historial)->groupBy('panel.created_date');
            } else
            {
                $insp_by_date[$insp->last->panel->created_date] = $insp;
            }

/*            foreach($insp->historial as $r) {
                $insp_by_date[$r->panel->created_date][] = $r;
            }*/
        }

        if(!$maquina)
        {
            $maquina = Maquina::orderBy('linea')->take(1)->first();
        }

        $output = compact('insp','insp_by_date','maquinas','maquina','timeline','barcode');

        return Response::multiple_output($output,'aoicollector.inspection.index');
    }

    public function searchReference($reference, $id_maquina, $turno, $fecha_eng, $progama,$realOFalso = 'real')
    {
        $search_reference = $reference;

        $panel_barcodes = $this->findPanelWithReference($id_maquina, $turno, $fecha_eng, $progama, $reference, $realOFalso);

        $maquina = Maquina::find($id_maquina);
        foreach($panel_barcodes as $p)
        {
            $insp[$p->panel_barcode] = PanelHistory::buscar($p->panel_barcode);
        }

        $maquinas = Maquina::orderBy('linea')->get();

        $output = compact('insp','maquinas','maquina','search_reference');

        return Response::multiple_output($output,'aoicollector.inspection.index');
    }

    public function findPanelWithReference($id_maquina, $turno, $fecha, $programa, $reference, $estado, $resume_type='first')
    {
        $query = "CALL aoidata.sp_getFindPanelWithReferenceFromHistory('".$id_maquina."','".$programa."','".$turno."','".$fecha."','".$reference."','".$estado."','".$resume_type."');";

        $sql = DB::connection('iaserver')->select($query);

        return $sql;
    }
}