<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory;
use IAServer\Http\Controllers\Aoicollector\Model\DetalleHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Maquina;

use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class InspectionController extends Controller
{
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

    public function defectosPeriodo()
    {
        $carbonDate = Util::dateRangeFilterEs('date_session');

        $maquinas = Maquina::select('maquina.*','produccion.inf','produccion.cogiscan')
            ->orderBy('maquina.linea')
            ->leftJoin('aoidata.produccion','produccion.id_maquina','=','maquina.id')
            ->get();

        $inspectionList = new InspectionList($maquinas->first()->id,$carbonDate->desde,$carbonDate->hasta);
        $defectChart = $inspectionList->queryDefectInspectionRange()->get();

        $maquina = $maquinas->first();

        $output = compact('defectChart','maquinas','maquina');

        return Response::multiple($output,'aoicollector.inspection.periodo_defectos');
    }

    public function listWithFilter($id_maquina,$pagina=null,$op='')
    {
        $id_maquina = (int) $id_maquina;

        $carbonDate = Util::dateRangeFilterEs('date_session');

        $inspectionList = new InspectionList($id_maquina,$carbonDate->desde,$carbonDate->hasta);
        $inspectionList->setPagina($pagina);
        $inspectionList->setMode(Input::get('listMode'));
        $inspectionList->setPeriod(Input::get('filterPeriod'));
        if(!empty($op))
        {
            $inspectionList->setOp($op);
        }

        $inspectionList->find();

        // Sidebar
        $maquinas = Maquina::select('maquina.*','produccion.cogiscan')
            ->orderBy('maquina.linea')
            ->leftJoin('aoidata.produccion','produccion.id_maquina','=','maquina.id')
            ->get();


        $maquina = $maquinas->where('id',$id_maquina)->first();

        $output = compact('defectChart','inspectionList','maquinas','maquina');

        return Response::multiple($output,'aoicollector.inspection.index');
    }

    /**
     * Muestra las inspecciones, filtradas por maquina
     *
     * @param $id_maquina
     * @param null $pagina
     * @return \Illuminate\View\View
     */
    public function listWithFilterORIGINAL($id_maquina,$pagina=null,$op='')
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

        return Response::multiple($output,'aoicollector.inspection.index');
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

        return Response::multiple($output,'aoicollector.inspection.partial.blocks');
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

        return Response::multiple($output,'aoicollector.inspection.partial.detail');
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
        $findService->withHistory = true;
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
        }

        if(!$maquina)
        {
            $maquina = Maquina::orderBy('linea')->take(1)->first();
        }

        $output = compact('insp','insp_by_date','maquinas','maquina','timeline','barcode');

        return Response::multiple($output,'aoicollector.inspection.search_barcode');
    }

    public function multipleSearchBarcode()
    {
        $regex = '/([0-9]+)/';

        $firstOrLast = Input::get('mode');
        if(!isset($firstOrLast))
        {
            $firstOrLast = 'first';
        }

        $input = Input::get('barcodes');
        preg_match_all($regex, $input, $matches);

        $barcodes = [];
        foreach ($matches[0] as $barcode) {

            $find = new FindInspection();
            $find->withSmt = true;
            $inspeccion = (object) $find->barcode($barcode);

            if(isset($inspeccion->error))
            {
                $barcodes[] = $inspeccion;
            } else
            {
                if($firstOrLast=='first')
                {
                    $barcodes[] = $inspeccion->first;
                } else
                {
                    $barcodes[] = $inspeccion->last;
                }
            }
        }

        $maquinas = Maquina::orderBy('linea')->get();
        $maquina = $maquinas->first();
        $output = compact('maquinas','maquina','barcodes');

        return Response::multiple($output,'aoicollector.inspection.multiplesearch');
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

        return Response::multiple($output,'aoicollector.inspection.search_reference');
    }

    public function findPanelWithReference($id_maquina, $turno, $fecha, $programa, $reference, $estado, $resume_type='first')
    {
        $query = "CALL aoidata.sp_getFindPanelWithReferenceFromHistory('".$id_maquina."','".$programa."','".$turno."','".$fecha."','".$reference."','".$estado."','".$resume_type."');";

        $sql = DB::connection('iaserver')->select($query);

        return $sql;
    }
}