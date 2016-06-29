<?php

namespace IAServer\Http\Controllers\Aoicollector\Stat;

use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public $debug;

    function __construct()
    {
        //$this->debug = new Debug($this);
    }

    public function aoiResume($id_maquina, $turno, $fecha, $programa, $op, $resume_type='first')
    {
        $query = "CALL aoidata.sp_getResumenFromHistoryWithOp('".$id_maquina."','".$programa."','".$turno."','".$fecha."','".$resume_type."','".$op."');";

        $sql = DB::connection('iaserver')->select($query);
        $resume = head($sql);

        if($resume)
        {
            $resume->total_paneles = $resume->inspecciones;
            $resume->total_bloques = $resume->total_paneles * $resume->bloques;
            unset($resume->inspecciones);
            return $this->calculateFpy($resume);

        } else {
            $resume = (object) array('inspecciones'=>0);
            return $resume;
        }
    }

    /**
     * Calcula el FPY del programa, segun los datos del resumen de aoi
     *
     * @param $resume
     * @return mixed
     */
    private function calculateFpy($resume)
    {
        $fpy_insp = round(($resume->ok_ins / $resume->total_bloques  ) * 100, 2);
        $fpy_aoi = round(($resume->ok_aoi / $resume->total_bloques  ) * 100, 2);
        $promedio_falso = round($resume->total_falso / $resume->total_bloques, 2);

        $lvl = StatController::dangerLevels($promedio_falso,$fpy_aoi, $fpy_insp);

        $resume->promedio_falso_error = $promedio_falso;
        $resume->fpy_insp = $fpy_insp;
        $resume->fpy_aoi = $fpy_aoi;
        $resume->level_insp = $lvl->insp;
        $resume->level_aoi = $lvl->aoi;
        $resume->level_falso = $lvl->falso;

        return $resume;
    }

    public static function dangerLevels($promedio_falso, $fpy_aoi, $fpy_insp)
    {
        $success_lvl = 3;
        $warning_lvl = 10;

        $lvl = new \stdClass();

        if($promedio_falso <= $success_lvl)
        {
            $lvl->falso = 'success';
        } elseif ($promedio_falso <= $warning_lvl)
        {
            $lvl->falso = 'warning';
        } else
        {
            $lvl->falso = 'danger';
        }

        if($fpy_insp>50)
        {
            $lvl->insp = 'success';
        } else
        {
            $lvl->insp = 'danger';
        }

        if($fpy_aoi>50)
        {
            $lvl->aoi = 'success';
        } else
        {
            $lvl->aoi = 'danger';
        }

        return $lvl;
    }

    public function referenceResume($id_maquina, $turno, $fecha, $programa, $op, $resume_type='first')
    {
        $query = "CALL aoidata.sp_getResumenBodyFromHistoryWithOp('".$id_maquina."','".$programa."','".$turno."','".$fecha."','".$resume_type."','".$op."');";

        $sql = DB::connection('iaserver')->select($query);

        $resume = array();

/*
        $referencias = array_unique(array_map(array($this, 'referenceFilterReferencias'),$sql),SORT_REGULAR);
        $lista_real = array_filter($sql,array($this, 'referenceFilterReal'));
        $lista_falso = array_filter($sql,array($this, 'referenceFilterFalso'));
*/

        foreach($sql as $r)
        {
            if(isset($resume[$r->referencia])) {
                // Sumo el valor actual al total.
                $resume[$r->referencia]->total = $resume[$r->referencia]->total + $r->total_errores;
            } else {
                $content = array();
                $content['referencia']= $r->referencia;
                $content['ultima_aparicion'] = $r->ultima_aparicion;
                $content['total'] = $r->total_errores;
                $content['total_real'] = 0;
                $content['total_falso'] = 0;

                $resume[$r->referencia] = (object) $content;
            }

            if($r->estado=='REAL') {
                $resume[$r->referencia]->total_real = $r->total_errores;
            }
            if($r->estado=='FALSO') {
                $resume[$r->referencia]->total_falso = $r->total_errores;
            }
        }

        $this->array_sort_by_column($resume, 'ultima_aparicion',SORT_DESC);

        return array_values($resume);
    }

    public function periodoInspeccion($id_maquina, $programa, $turno, $fecha)
    {
        $query = "CALL aoidata.sp_getPeriodo_opt('".$id_maquina."','".$programa."','".$turno."','".$fecha."');";
        $sql = DB::connection('iaserver')->select($query);

        return $sql;
    }

    public function infoReferenciaDetalle($id_maquina, $programa, $turno, $fecha, $referencia, $filtro_estado, $op, $resume_type)
    {
        //$query = "CALL aoidata.sp_getPeriodoDescripcionFromHistoryWithOp('".$id_maquina."','".$programa."','".$turno."','".$fecha."','".$referencia."','".$filtro_estado."','".$resume_type."','".$op."');";
        $query = "SELECT
        d.referencia,
        f.descripcion,
        COUNT(d.faultcode) as total

        FROM `aoidata`.`history_inspeccion_detalle` d
        INNER JOIN `aoidata`.history_inspeccion_bloque b on b.id_bloque_history = d.id_bloque_history
        INNER JOIN `aoidata`.history_inspeccion_panel p on p.id_panel_history = b.id_panel_history
        LEFT JOIN `aoidata`.rns_faultcode f on f.faultcode = d.faultcode

        WHERE
            p.created_date = '".$fecha."' AND
            p.programa = '".$programa."' and
            p.turno = '".$turno."'  and
            d.referencia = '".$referencia."' and
            p.id_maquina = ".$id_maquina." AND
            p.inspected_op = '".$op."' AND
            d.estado = '".$filtro_estado."' 	AND

            p.`created_time` = (
                    select ".( $resume_type == 'first' ? 'MIN' : 'MAX' )."(h.created_time) from `aoidata`.`history_inspeccion_panel` h
                    where
                    h.`panel_barcode` = p.panel_barcode and
                    h.`programa` = '".$programa."' and
                    h.`turno` = '".$turno."'   and
                    h.`id_maquina` = ".$id_maquina." and
                    h.`created_date` = '".$fecha."'  and
                    h.`inspected_op` = '".$op."'
                    group by h.`panel_barcode`, h.`id_maquina`, h.`inspected_op`
                )

        GROUP BY
            d.referencia ,
            d.faultcode

        ORDER BY
        total DESC;
        ";

        $sql = DB::connection('iaserver')->select($query);

        return $sql;
    }

    private function array_sort_by_column(&$arr, $propiedad, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $r)
        {
            $sort_col[$r->referencia] = $r->$propiedad;
        }
        array_multisort($sort_col, $dir, $arr);
    }

    public function referenceFilterReal($arr)
    {
        if($arr->estado == 'REAL') {
            return $arr;
        }
    }

    public function referenceFilterFalso($arr)
    {
        if($arr->estado == 'FALSO') {
            return $arr;
        }
    }

    public function referenceFilterReferencias($arr)
    {
        return $arr->referencia;
    }
}
