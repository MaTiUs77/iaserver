<?php
namespace IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip;

use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\XXEWipITFSerieHistory;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\DB;

class WipSerieHistory extends WipSerieCommons
{
    public $class = 'IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\XXEWipITFSerieHistory';

    public function findBarcode($barcode,$op="",$transOk="",$like="")
    {
        $serie = XXEWipITFSerieHistory::select([
                'id',
                'nro_op',
                'nro_informe',
                'codigo_producto',
                'cantidad',
                'referencia_1',
                'fecha_proceso',
                'trans_ok',
                'ebs_error_desc',
                'ebs_error_trans',
                'fecha_insercion']
        )
            ->where('organization_code','UP3');

        if(empty($like))
        {
            $serie->where('referencia_1',$barcode);
        } else
        {
            $serie->where('referencia_1','like',$like);
        }

        if(!empty($op))
        {
            $serie->where('nro_op',$op);
        }

        if(is_numeric($transOk))
        {
            $serie->where('trans_ok',$transOk);
        }

        $result = $serie->orderBy('fecha_insercion','desc')->get();

        return $result;
    }

    public function period($op, $minutes = 60)
    {
        $sql = XXEWipITFSerieHistory::select(
            DB::raw("
                SUM(\"CANTIDAD\") as total,
                nro_op as op,
                DATEPART(YEAR, FECHA_INSERCION) as anio,
                DATEPART(MONTH, FECHA_INSERCION) as mes,
                DATEPART(DAY, FECHA_INSERCION) as dia,
                DATEPART(HOUR, FECHA_INSERCION) as periodo,
                (DATEPART(MINUTE, FECHA_INSERCION) / ".$minutes.") as minuto
            ")
        )
            ->where('nro_op',$op)
            ->groupBy(DB::raw("
                nro_op,
                DATEPART(YEAR, FECHA_INSERCION),
                DATEPART(MONTH, FECHA_INSERCION),
                DATEPART(DAY, FECHA_INSERCION),
                DATEPART(HOUR, FECHA_INSERCION),
                (DATEPART(MINUTE, FECHA_INSERCION) / ".$minutes.")
            "))
            ->orderBy(DB::raw("
                DATEPART(MONTH, FECHA_INSERCION) asc,
                DATEPART(DAY, FECHA_INSERCION) asc,
                DATEPART(HOUR, FECHA_INSERCION)
            "));

        return $sql;
    }
}
