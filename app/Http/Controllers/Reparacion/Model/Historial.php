<?php

namespace IAServer\Http\Controllers\Reparacion\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;

class Historial extends Model
{
    protected $connection = 'reparacion';
    protected $table = 'reparacion.historial';

    private function listarHistorial($id_sector)
    {
        $sql = Historial::select([
            'historial.id',
            'historial.codigo',
            'operador.nombre',
            'operador.apellido',
            DB::raw('
                CONCAT(operador.nombre," ",operador.apellido) as nombre_completo
            '),
            'historial.modelo',
            'historial.lote',
            'historial.panel',
            'causa.causa',
            'defecto.defecto',
            'historial.defecto as referencia',
            'accion.accion',
            'origen.origen',
            'historial.correctiva',
            'historial.estado',
            'turno.turno',
            'historial.fecha',
            'historial.hora',
            'sector.sector',
            'area.area',
            DB::raw('
            (
                SELECT
                    COUNT(*)
                FROM reparacion.fotos f
                WHERE
                    f.codigo = historial.codigo AND
                    f.id_sector = '. $id_sector.'
                ORDER BY f.codigo

                ) as fotos'
            ),
            DB::raw('
            (
                SELECT
                    COUNT(*)
                FROM reparacion.reparacion rc
                WHERE
                    rc.estado = "R" AND
                    rc.codigo = historial.codigo AND
                    rc.id_sector = '. $id_sector.'
                ORDER BY rc.codigo

                ) as reparaciones'
            ),
            DB::raw('
                IF(
                    (
                        SELECT
                            STR_TO_DATE(CONCAT(rh.fecha," ",rh.hora), "%Y-%m-%d %H:%i:%s")
                        FROM reparacion.reparacion rh
                        WHERE
                            rh.codigo = historial.codigo AND
                            rh.id_sector = '. $id_sector.'
                        ORDER BY rh.id desc
                        LIMIT 1
                    ) = STR_TO_DATE(CONCAT(historial.fecha," ",historial.hora), "%Y-%m-%d %H:%i:%s"), "actual","log"
                ) as historico
            ')
        ])
            ->leftJoin('reparacion.causa','reparacion.causa.id','=','reparacion.historial.id_causa')
            ->leftJoin('reparacion.defecto','reparacion.defecto.id','=','reparacion.historial.id_defecto')
            ->leftJoin('reparacion.accion','reparacion.accion.id','=','reparacion.historial.id_accion')
            ->leftJoin('reparacion.origen','reparacion.origen.id','=','reparacion.historial.id_origen')
            ->leftJoin('reparacion.turno','reparacion.turno.id','=','reparacion.historial.id_turno')
            ->leftJoin('reparacion.sector','reparacion.sector.id','=','reparacion.historial.id_sector')
            ->leftJoin('reparacion.area','reparacion.area.id','=','reparacion.historial.id_area')
            ->leftJoin('reparacion.operador','reparacion.operador.id','=','reparacion.historial.id_operador')
            ->where('historial.id_sector',$id_sector)->orderBy('historial.fecha','desc')->orderBy('historial.hora','desc');

        return $sql;
    }

    public function scopeListarSector($query, $id_sector, $fecha_desde="", $fecha_hasta="")
    {
        $sql = $this->listarHistorial($id_sector);

        if($fecha_desde != '')
        {
            if($fecha_hasta == '')
            {
                if($fecha_desde == 'curdate')
                {
                    $sql->whereRaw('historial.fecha = CURDATE() ');
                } else
                {
                    $sql->whereRaw('historial.fecha = "'.$fecha_desde.'"');
                }
            } else
            {
                $sql->whereRaw('historial.fecha >= "'.$fecha_desde.'"');
                $sql->whereRaw('historial.fecha <= "'.$fecha_hasta.'"');
            }
        }

        return $sql;
    }

    public function scopeBarcode($query, $id_sector,$barcode)
    {
        $sql = $this->listarSector($id_sector);
        $sql->where('historial.codigo',$barcode);

        return $sql;
    }
}
