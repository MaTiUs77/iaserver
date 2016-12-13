<?php

namespace IAServer\Http\Controllers\Reparacion;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Reparacion\Model\Historial;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ReparacionController extends Controller
{
    function __construct()
    {
        /*
        Event::listen('illuminate.query',function($query,$params,$time,$conn){
            Log::debug(array($query,$params,$time,$conn));
        });
        */
    }

    public function getReporte() {

        // Crea una session para filtro de fecha
        $range = Util::dateRangeFilterEs('reparacion_fecha');
        Filter::makeSession('id_sector',25);

        $id_sector = Session::get('id_sector');

        if(is_numeric($id_sector) && $id_sector>0) {

            $reparacion = Historial::listarSector(
                $id_sector,
                $range->desde->toDateString(),
                $range->hasta->toDateString()
            )->get();

            $resumen = new \stdClass();
            $resumen->rechazos = $reparacion->where('estado', 'P')->count();
            $resumen->reparaciones = $reparacion->where('estado', 'R')->count();
            $resumen->pendientes = $reparacion->where('estado', 'P')->where('historico', 'actual')->count();
            $resumen->scrap = $reparacion->where('estado', 'S')->count();
            $resumen->bonepile = $reparacion->where('estado', 'B')->count();
            $resumen->analisis = $reparacion->where('estado', 'A')->count();

            $causas = $reparacion->groupBy('causa');
            $defectos = $reparacion->groupBy('defecto');
            $referencias = $reparacion->groupBy('referencia');
            $acciones = $reparacion->groupBy('accion');
            $origenes = $reparacion->groupBy('origen');
            $reparadores = $reparacion->groupBy('nombre_completo');
            $turnos = $reparacion->groupBy('turno');

            $stats = new \stdClass();
            $stats->defectos = collect($this->extendHistorialCollection($defectos))->sortByDesc('rechazos');
            $stats->causas = collect($this->extendHistorialCollection($causas))->sortByDesc('rechazos');
            $stats->referencias = collect($this->extendHistorialCollection($referencias))->sortByDesc('rechazos');
            $stats->acciones = collect($this->extendHistorialCollection($acciones))->sortByDesc('rechazos');
            $stats->origenes = collect($this->extendHistorialCollection($origenes))->sortByDesc('rechazos');
            $stats->reparadores = collect($this->extendHistorialCollection($reparadores))->sortByDesc('rechazos');
            $stats->turnos = collect($this->extendHistorialCollection($turnos))->sortByDesc('rechazos');
        } else
        {
            $id_sector = null;
        }

        $output = compact('id_sector','resumen','stats','reparacion');

        return Response::multiple($output,'reparacion.index');
    }

    public function extendHistorialCollection(Collection $collection)
    {
        foreach($collection as $key => $items)
        {
            $collection[$key] = new \stdClass();
            $collection[$key]->rechazos = $items->where('estado','P')->count();
            $collection[$key]->reparados = $items->where('estado','R')->count();
            $collection[$key]->pendientes =  $items->where('estado','P')->where('historico','actual')->count();
            $collection[$key]->scrap = $items->where('estado','S')->count();
            $collection[$key]->bonepile = $items->where('estado','B')->count();
            $collection[$key]->analisis = $items->where('estado','A')->count();
            $collection[$key]->items= $items;
        }

        return $collection;
    }

    // EN FASE DE DESARROLLO
    public function extendHistorialCollectionAndSplitKey(Collection $collection)
    {
        foreach($collection as $key => $items)
        {
            if(str_contains($key,','))
            {
                foreach (explode(',',$key) as $rekey) {
                    if(isset($collection[$rekey]))
                    {
                        if($collection[$rekey] instanceof \Illuminate\Support\Collection)
                        {
                            $oldContent = $collection[$rekey];
                            $collection[$rekey] = new \stdClass();
                            $collection[$rekey]->reparados = $items->where('estado','R')->count();
                            $collection[$rekey]->pendientes =  $items->where('estado','P')->where('historico','actual')->count();
                            $collection[$rekey]->rechazos = $items->where('estado','P')->count();
                            $collection[$rekey]->scrap = $items->where('estado','S')->count();
                            $collection[$rekey]->bonepile = $items->where('estado','B')->count();
                            $collection[$rekey]->analisis = $items->where('estado','A')->count();
                            $collection[$rekey]->oldcontent = $oldContent;
                            dump("============Support\Collection ".$rekey."=================",$collection,$collection[$rekey]);

                        } else
                        {
                            $collection[$rekey]->pendientes += $items->where('estado','P')->where('historico','actual')->count();
                            $collection[$rekey]->rechazos += $items->where('estado','P')->count();
                            $collection[$rekey]->scrap += $items->where('estado','S')->count();
                            $collection[$rekey]->bonepile += $items->where('estado','B')->count();
                            $collection[$rekey]->analisis += $items->where('estado','A')->count();
//                        $collection[$rekey]->items = $items;

                            dump("============Is Object ".$rekey."=================",$collection,$collection[$rekey]);

                        }
                    } else
                    {
                        $collection[$rekey] = new \stdClass();
                        $collection[$rekey]->reparados = $items->where('estado','R')->count();
                        $collection[$rekey]->pendientes =  $items->where('estado','P')->where('historico','actual')->count();
                        $collection[$rekey]->rechazos = $items->where('estado','P')->count();
                        $collection[$rekey]->scrap = $items->where('estado','S')->count();
                        $collection[$rekey]->bonepile = $items->where('estado','B')->count();
                        $collection[$rekey]->analisis = $items->where('estado','A')->count();
                        $collection[$rekey]->items= $items;

                        dump('splid add',$rekey,$key,$collection[$key], $collection[$rekey]);

                    }
                }
                //unset($collection[$key]);

            } else
            {
                $collection[$key] = new \stdClass();
                $collection[$key]->reparados = $items->where('estado','R')->count();
                $collection[$key]->pendientes =  $items->where('estado','P')->where('historico','actual')->count();
                $collection[$key]->rechazos = $items->where('estado','P')->count();
                $collection[$key]->scrap = $items->where('estado','S')->count();
                $collection[$key]->bonepile = $items->where('estado','B')->count();
                $collection[$key]->analisis = $items->where('estado','A')->count();
                $collection[$key]->items= $items;

                if($key=='Q86')
                {
                    dump("============ADDED ".$key."=================",$collection[$key]);
                }
            }
        }

        dd($collection);
        return $collection;
    }

    public function getBarcode($id_sector, $barcode) {
        $reparacion = Historial::barcode($id_sector, $barcode)->get();

        $output = compact('reparacion');
        return Response::multiple($output,'reparacion.index');
    }

    public function postFilter()
    {
        $id_sector = Input::get('id_sector');
        $fecha_desde = Input::get('fecha_desde');
        $fecha_hasta = Input::get('fecha_hasta');

        $barcode= Input::get('barcode');
        $referencia = Input::get('referencia');

        $modelo= Input::get('modelo');
        $lote = Input::get('lote');
        $panel = Input::get('panel');

        $id_area = Input::get('id_area');
        $id_turno = Input::get('id_turno');
        $id_operador = Input::get('id_operador');

        $estados = Input::get('estados');

        $reparacion = array();

        if(!empty($barcode)) {
            $reparacion = Historial::barcode($id_sector, $barcode)->get();
        } else {
            $reparacion = Historial::listarSector($id_sector, $fecha_desde, $fecha_hasta);

            if(!empty($modelo)) { $reparacion->where('historial.modelo',$modelo); }
            if(!empty($lote)) { $reparacion->where('historial.lote',$lote); }
            if(!empty($panel)) { $reparacion->where('historial.panel',$panel); }
            if(!empty($id_area)) { $reparacion->where('historial.id_area',$id_area); }
            if(!empty($id_turno)) { $reparacion->where('historial.id_turno',$id_turno); }
            if(!empty($id_operador)) { $reparacion->where('historial.id_operador',$id_operador); }
            if(!empty($referencia)) { $reparacion->where('historial.defecto','like','%'.$referencia.'%'); }

            if(!empty($estados))
            {
                $list_estados = explode('|',$estados);

                if(count($list_estados)>0)
                {
                    $prepare_list = array();
                    foreach($list_estados as $estado)
                    {
                        $prepare_list[] = ' historial.estado="'.$estado.'" ';
                    }

                    $reparacion->whereRaw("( ".join(' or ',$prepare_list).")");
                }
            }

            $reparacion = $reparacion->get();
        }
        $output = compact('reparacion');
        return Response::multiple($output,'reparacion.index');
    }
}
