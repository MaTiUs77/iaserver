<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\Maquina;

use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Exporta las inspecciones de una maquina especifica
 *
 * @package IAServer\Http\Controllers\Aoicollector\Inspection
 */
class InspectionExport extends Controller
{
    /**
     * Exporta la lista de inspecciones en formato excel
     *
     * @param int $id_maquina
     * @param string $fecha Actualmente en desuso, utiliza una fecha definida en la Session inspection_date_session
     * @param string $minOrMax Existen 3 modos MIN, MINA y MAX
     *
     * @return mixed Crea un downstream de un archivo XLS
     */
    public function toExcel($id_maquina,$fecha,$minOrMax)
    {
        $maquina = Maquina::findOrFail($id_maquina);

        $carbonDate = Util::dateRangeFilterEs('inspection_date_session');

        $inspectionList = new InspectionList($carbonDate->desde,$carbonDate->hasta);
        $inspectionList->setIdMaquina($id_maquina);
        $inspectionList->setMode($minOrMax);
        $inspectionList->programsUsedByIdMaquina();
        $inspectionList->getPaneles();

        $filename =  'SMD-'.$maquina->linea.'_'.$carbonDate->desde->format('d-m-Y');

        $inspectionList->inspecciones->makeHidden([
            'id_panel_history',
            'modo',
            'id',
            'id_maquina',
            'fecha',
            'hora',
            'test_machine_id',
            'program_name_id',
            'pendiente_inspeccion',
            'etiqueta',
            'id_user',
            'first_history_inspeccion_panel'
        ]);

        Excel::create('Stat_'.$filename, function($excel) use($inspectionList,$filename) {

            $excel->sheet($filename, function($sheet) use($inspectionList) {
                $sheet->setOrientation('landscape');
                //$sheet->fromArray($inspectionList->inspecciones);
                $sheet->fromModel($inspectionList->inspecciones);
            });

        })->download('xls');
    }
}