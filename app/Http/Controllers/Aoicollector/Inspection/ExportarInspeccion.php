<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Maquina;

use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ExportarInspeccion extends Controller
{
    public function toCsv($id_maquina,$fecha,$minOrMax)
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
}