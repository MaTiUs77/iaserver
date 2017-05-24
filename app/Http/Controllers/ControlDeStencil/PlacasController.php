<?php

namespace IAServer\Http\Controllers\ControlDeStencil;

use Carbon\Carbon;
use Illuminate\Http\Request;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PlacasController extends Controller
{
    public static function init()
    {
        return view('controldestencil.reporte');
    }

    public function exportToExcel(Request $request)
    {
        $fecha = Carbon::now();
        Excel::create('Reporte de Lavado de Placas - '.$fecha, function($excel) use($request) {
            $excel->sheet('Lavado', function($sheet) use($request) {
                $sheet->setOrientation('landscape');
                $array = ABMLavado::getAll();
//                $newArr = $array->map(function ($item) {
//                    unset(
//                        $item->maquina,
//                        $item->modulo,
//                        $item->tabla,
//                        $item->feeder,
//                        $item->programa,
//                        $item->fecha
//                    );
//                    return $item;
//                });
                $sheet->fromArray($array,null,'A2',false,false);
                $sheet->row(1,array('LINEA','CODIGO','RESPONSABLE','FECHA'));
            });})->export('xls');
    }
}
