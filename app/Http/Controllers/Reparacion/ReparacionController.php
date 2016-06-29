<?php

namespace IAServer\Http\Controllers\Reparacion;

use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Reparacion\Model\Historial;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

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

    public function getSector($id_sector, $fecha_desde, $fecha_hasta="") {
        $reparacion = Historial::listarSector($id_sector, $fecha_desde, $fecha_hasta)->get();

        $output = compact('reparacion');
        return Response::multiple_output($output,'reparacion.index');
    }

    public function getBarcode($id_sector, $barcode) {
        $reparacion = Historial::barcode($id_sector, $barcode)->get();

        $output = compact('reparacion');
        return Response::multiple_output($output,'reparacion.index');
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
        return Response::multiple_output($output,'reparacion.index');
    }
}
