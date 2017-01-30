<?php

namespace IAServer\Http\Controllers\Inventario;

use IAServer\Http\Controllers\Inventario\Model\impresiones;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Request;


class excelController extends Controller
{
    public function index (Request $request)
    {
        if($request->get('plantas') == "all")
        {
            Excel::create('Inventario 2016', function($excel){
                $excel->sheet('Impresiones', function($sheet){
                    $fechaInicio = substr($_REQUEST['pizarra_fecha'],0,10);
                    $fechaFinal = substr($_REQUEST['pizarra_fecha'],13,20);
                    $impresion = impresiones::SELECT(DB::RAW('lpn.lpn,i.id_responsable_imp,i.id_partnumber,i.cant_agregada,i.seg_conteo,
               i.ter_conteo,m.descripcion,m.desc_u_medida,m.unidad_medida,s.descripcion as sector,p.descripcion as planta,i.fecha_impresion'))
                        ->FROM('impresiones as i')
                        ->LEFTJOIN('materiales as m','i.id_partnumber','=','m.codigo')
                        ->LEFTJOIN('lpn_generator as lpn','i.id_etiqueta','=','lpn.id')
                        ->LEFTJOIN('sector as s','i.id_zona','=','s.id_sector')
                        ->LEFTJOIN('planta as p','i.id_planta','=','p.id_planta')
                        ->WHERE('i.fecha_impresion','>',$fechaInicio.' 06:00:00')
                        ->WHERE('i.fecha_impresion','<',$fechaFinal.' 15:00:00')
                        ->ORDERBY('lpn.lpn','desc')
                        ->GET();
                    $sheet->fromArray($impresion);
                });
            })->export('xls');
        }else{
            Excel::create('Inventario 2016', function($excel){
                $excel->sheet('Impresiones', function($sheet){
                    $planta = $_REQUEST['plantas'];
                    $fechaInicio = substr($_REQUEST['pizarra_fecha'],0,10);
                    $fechaFinal = substr($_REQUEST['pizarra_fecha'],13,20);
                    $impresion = impresiones::SELECT(DB::RAW('lpn.lpn,i.id_responsable_imp,i.id_partnumber,i.cant_agregada,i.seg_conteo,
               i.ter_conteo,m.descripcion,m.desc_u_medida,m.unidad_medida,s.descripcion as sector,p.descripcion as planta,i.fecha_impresion'))
                        ->FROM('impresiones as i')
                        ->LEFTJOIN('materiales as m','i.id_partnumber','=','m.codigo')
                        ->LEFTJOIN('lpn_generator as lpn','i.id_etiqueta','=','lpn.id')
                        ->LEFTJOIN('sector as s','i.id_zona','=','s.id_sector')
                        ->LEFTJOIN('planta as p','i.id_planta','=','p.id_planta')
                        ->WHERE('i.fecha_impresion','>',$fechaInicio.' 06:00:00')
                        ->WHERE('i.fecha_impresion','<',$fechaFinal.' 15:00:00')
                        ->WHERE('i.id_planta',$planta)
                        ->ORDERBY('lpn.lpn','desc')
                        ->GET();
                    $sheet->fromArray($impresion);
                });
            })->export('xls');
        }

    }
}