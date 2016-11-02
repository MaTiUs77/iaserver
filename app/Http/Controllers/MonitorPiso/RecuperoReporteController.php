<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use IAServer\Http\Controllers\IAServer\Util;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;


class RecuperoReporteController extends Controller
{
    public function show()
    {

        return view('monitorpiso.recupero_reporte',['lineas'=>$this->getLines()]);
    }

    public function filter()
    {

    }

    public function find()
    {
        $datos = $this->getDataToShow();
        return view('monitorpiso.recupero_reporte',['lineas'=>$this->getLines(),'datos'=>$datos]);
    }

    public function getLines()
    {
        $dbController = new DBController();
        $lineas = $dbController->getLines();
        return $lineas;
    }

    public function getDataToShow($export=false)
    {
        if(!$export) {
            $linea = Input::only('ddLinea');
            $range = Util::dateRangeFilterEs('pizarra_fecha');
            Session::set('linea', $linea);
            Session::set('desde', $range->desde);
            Session::set('hasta', $range->hasta);
        }
        $dbController = new DBController();
        $datos = $dbController->historialRecupero(Session::get('linea'),Session::get('desde'),Session::get('hasta'));
        return $datos;
    }

    private function formatArray()
    {
        $datos = $this->getDataToShow(true);
        return $datos;
    }

    public function export()
    {
        Excel::create('Reporte de Recuperación', function($excel) {

            $excel->sheet('Reporte de Recuperación', function($sheet) {
                $sheet->setOrientation('landscape');
                $datos = $this->formatArray();
                $sheet->fromArray($datos,null,'A2',false,false);
                $sheet->row(1,array('LPN','Part Number','Cantidad Recuperada','Contenido En','Ubicación en el Contenedor','OP','Línea','Usuario','Fecha de Recuperación'));
            });
        })->export('xls');
    }


}
