<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Maquina;

use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class InspectionExport extends Controller
{
    public function toExcel($id_maquina,$fecha,$minOrMax)
    {
        $maquina = Maquina::findOrFail($id_maquina);
        $fecha = Util::dateToEn(Session::get('date_session'));

        $insp = PanelHistory::listar($id_maquina, $fecha, "",$minOrMax)->get();
        $filename = 'SMD-'.$maquina->linea.'_'.$fecha;

        $insp->makeHidden([
            'id_panel_history',
            'modo',
            'id',
            'id_maquina',
            'fecha',
            'hora',
            'test_machine_id',
            'program_name_id',
            'pendiente_inspeccion',
            'etiqueta'
        ]);

        Excel::create('Stat_'.$filename, function($excel) use($insp,$filename) {

            $excel->sheet($filename, function($sheet) use($insp) {
                $sheet->setOrientation('landscape');
                $sheet->fromModel($insp);
            });

        })->download('xls');
    }
/*
    public function collectionToExcel($filename, $collection)
    {
        Excel::create('Export_'.$filename, function($excel) use($collection,$filename) {

            $excel->sheet($filename, function($sheet) use($collection) {
                $sheet->setOrientation('landscape');
                $sheet->fromModel($collection);
            });

        })->download('xls');
    }*/
}