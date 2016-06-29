<?php
namespace IAServer\Http\Controllers\Aoicollector\Stat;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\Maquina;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\StatResume;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Log;

class StatExport extends StatController
{
    public function __construct()
    {
        parent::__construct();
        set_time_limit(60 * 5);
    }

    public function allMachinesToDb($firstLine,$lastLine,$fecha="")
    {
        if(empty($fecha))
        {
            $fecha = Carbon::now()->toDateString();
        }

        $maquinas = Maquina::where('linea','>=',$firstLine)->where('linea','<=',$lastLine)->orderBy('linea')->get(); //all()->unique('linea');

        Log::info("ARTISAN: $fecha");
        foreach ($maquinas as $maquina)
        {
            $this->toDb($maquina,$fecha);
        }
    }

    public function toDb($maquina,$fecha,$resume_type='first')
    {
        $estado = 'real';
        //$csv = array();

      //  $filename = 'Stats_TODB-'.$maquina->linea.'_'.$fecha.'.csv';

        $existQuery = StatResume::where('fecha',$fecha)
            ->where('linea','SMD-'.$maquina->linea)
            ->where('id_maquina',$maquina->id)
            ->where('resume_type',$resume_type);

        if(count($existQuery->get())>0)
        {
            $existQuery->delete();
        }

        $current_csv = $this->allTurns($maquina,$fecha, $estado, $resume_type);

        foreach($current_csv as $format)
        {
            //$csv[] = $format;
            $item = (object) $format;

            $toResume = new StatResume();
            $toResume->fecha = $item->fecha;
            $toResume->turno = $item->turno;
            $toResume->linea = $item->linea;
            $toResume->programa = $item->programa;
            $toResume->op = $item->op;

            $smt = SMTDatabase::findOp($item->op);
            if(isset($smt))
            {
                $toResume->modelo = $smt->modelo;
                $toResume->lote = $smt->lote;
                $toResume->panel = $smt->panel;
            } else
            {
                $toResume->modelo = '';
                $toResume->lote = '';
                $toResume->panel = '';
            }

            $toResume->total_paneles = $item->total_paneles;
            $toResume->total_falso = $item->total_falso;
            $toResume->total_real = $item->total_real;
            $toResume->promedio_falso_error = $item->promedio_falso_error;
            $toResume->ng_aoi = $item->ng_aoi;
            $toResume->ng_insp = $item->ng_insp;
            $toResume->posicion = $item->posicion;
            $toResume->defecto = $item->defecto;
            $toResume->estado = $item->estado;
            $toResume->total_posicion = $item->total_posicion;
            $toResume->total_defecto_real = $item->total_defecto_real;
            $toResume->porcentaje_posicion_real = $item->porcentaje_posicion_real;
            $toResume->total_bloques = $item->total_bloques;
            $toResume->excecuted_at = Carbon::now();
            $toResume->resume_type = $resume_type;
            $toResume->id_maquina = $maquina->id;

            $toResume->save();
        }

        Log::info("StatExport: toDb() SMD-$maquina->linea $fecha $resume_type (idMaquina: $maquina->id) ");
    }

    public function toCsv($linea, $turno, $fecha, $resume_type, $programa="")
    {
        $fecha = Util::dateToEn($fecha);

        $estado = 'real';
        $csv = array();

        $maquinas = Maquina::where('linea',$linea)->get();

        $filename = 'Stats_SMD-'.$linea.'_'.$fecha.'.csv';

        foreach ($maquinas as $maquina)
        {
            $current_csv = $this->allTurns($maquina,$fecha, $estado, $resume_type);

            foreach($current_csv as $format)
            {
                $csv[] = $format;
            }
        }

        Log::info("StatExport: toCsv() SMD-$maquina->linea $fecha $resume_type (idMaquina: $maquina->id) ");
        Util::convert_to_csv($csv,$filename,',');
    }

    protected function allTurns($maquina, $fecha, $estado, $resume_type)
    {
        $turnos = ['M','T'];

        foreach($turnos as $turno)
        {
            $programas = PanelHistory::programUsed($maquina->id, $fecha, $turno);

           if(count($programas)==0)
            {

                $csv[] = array(
                    'fecha' => $fecha,
                    'turno' => $turno,
                    'linea' => 'SMD-'.$maquina->linea,
                    'programa' => 'SIN HISTORIAL',
                    'op' => 'SIN OP',

                    'modelo' => 'SIN DEFINIR',
                    'lote' => 'SIN DEFINIR',
                    'panel' => 'SIN DEFINIR',

                    'total_paneles' => 0,
                    'total_bloques' => 0,
                    'total_falso' => 0,
                    'total_real' => 0,
                    'promedio_falso_error' => 0,
                    'ng_aoi' => 0,
                    'ng_insp' => 0,
                    'posicion' => '',
                    'defecto' => '',
                    'estado' => '',
                    'total_posicion' => 0,
                    'total_defecto_real' => 0,
                    'porcentaje_posicion_real' => 0
                );

            } else {
                foreach($programas as $prog)
                {
                    $result = $this->inspectionByProgram($maquina->linea,$prog->id_maquina,$turno,$prog->programa,$fecha,$estado,$prog->inspected_op, $resume_type);

                    foreach($result as $r)
                    {
                        $csv[] = $r;
                    }
                }
            }
        }

        return $csv;
    }

    public function inspectionByProgram($linea, $id_maquina,$turno,$programa,$fecha, $estado, $op, $resume_type)
    {
        $resume = $this->aoiResume($id_maquina, $turno, $fecha, $programa, $op, $resume_type);
        $reference = $this->referenceResume($id_maquina, $turno, $fecha, $programa,$op, $resume_type);

        $output = array();

        $smt = SMTDatabase::findOp($op);

        // Mientras existan referencias
        foreach($reference as $ref)
        {
            // Solo obtengo referencias con errores REALES
            if($ref->total_real>0)
            {
                $detalle = $this->infoReferenciaDetalle($id_maquina,$programa,$turno,$fecha, $ref->referencia, $estado, $op, $resume_type);

                foreach($detalle as $det)
                {
                    // Si tengo errores reales, adjunto informacion de apariciones
                    $ref_resume = head(array_filter($reference, function($elem) use($ref){
                        if($elem->referencia == $ref->referencia)
                        {
                            return $elem;
                        }
                    }));

                    // Genero una linea por cada referencia / faultcode
                    $output[] = array(
                        'fecha' => $fecha,
                        'turno' => $turno,
                        'linea' => 'SMD-'.$linea,
                        'programa' => $programa,
                        'op' => $resume->inspected_op,

                        'modelo' => $smt->modelo,
                        'lote' => $smt->lote,
                        'panel' => $smt->panel,

                        'total_paneles' => $resume->total_paneles,
                        'total_bloques' => $resume->total_bloques,
                        'total_falso' => $resume->total_falso,
                        'total_real' => $resume->total_real,
                        'promedio_falso_error' => $resume->promedio_falso_error,
                        'ng_aoi' => $resume->ng_aoi,
                        'ng_insp' => $resume->ng_ins,
                        'posicion' => $ref->referencia,
                        'defecto' => ($det->descripcion == null) ? 'Descripcion desconocida' : $det->descripcion,
                        'estado' => $estado,
                        'total_posicion' => $ref_resume->total,
                        'total_defecto_real' => $det->total,
                        'porcentaje_posicion_real' => ($ref_resume->total > 0) ?  number_format($det->total * 100 / $ref_resume->total,2) . '%' : 0,
                    );
                }
            }
        }

        // No se detectaron errores reales?, genero una linea con datos de inspecciones en el dia.
        if(count($output)==0)
        {
            $output[] = array(
                'fecha' => $fecha,
                'turno' => $turno,
                'linea' => 'SMD-'.$linea,
                'programa' => $programa,
                'op' => $resume->inspected_op,

                'modelo' => $smt->modelo,
                'lote' => $smt->lote,
                'panel' => $smt->panel,

                'total_paneles' => $resume->total_paneles,
                'total_bloques' => $resume->total_bloques,
                'total_falso' => $resume->total_falso,
                'total_real' => $resume->total_real,
                'promedio_falso_error' => $resume->promedio_falso_error,
                'ng_aoi' => $resume->ng_aoi,
                'ng_insp' => $resume->ng_ins,
                'posicion' => '',
                'defecto' => '',
                'estado' => '',
                'total_posicion' => 0,
                'total_defecto_real' => 0,
                'porcentaje_posicion_real' => 0
            );
        }

        return $output;
    }
}
