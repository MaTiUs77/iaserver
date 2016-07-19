<?php

namespace IAServer\Http\Controllers\Controldeplacas;

use IAServer\Http\Controllers\Controldeplacas\Model\Datos;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DatosController extends Controller
{
    public static function salida($modelo,$lote, $placa) {
        $query = "SELECT
            d.modelo,
            d.lote
            ,d.placa
            ,lotes.total as total_lote

            ,(
                SELECT SUM(cantidad) as salidas
                FROM placas_dev.datos
                WHERE
                    modelo = d.modelo
                and lote = d.lote
                and placa = d.placa
                and id_sector = d.id_sector
                GROUP BY modelo,lote,placa,id_sector
            ) AS salidas

            ,(
                SELECT ( SUM(cantidad) - lotes.total ) as restantes
                FROM placas_dev.datos
                WHERE
                    modelo = d.modelo
                and lote = d.lote
                and placa = d.placa
                and id_sector = d.id_sector
                GROUP BY modelo,lote,placa,id_sector
            ) as diferencia

            FROM
            placas_dev.datos d

            left join (
                select id,modelo, lote, total
                from placas_dev.lotes
            ) as lotes
            on d.modelo = lotes.modelo and d.lote = lotes.lote

             where
              d.id_sector = 2
             and d.modelo = '".$modelo."'
             and d.placa = '".$placa."'
            and d.lote = '".$lote."'

            group by d.modelo,d.lote,d.placa

            order by d.lote asc";

        $sql = DB::connection('iaserver')->selectOne($query);

        if(!$sql) {
            $sql = array("error" => "Sin datos");
        }

        return $sql;
    }

    public static function salidaByOpList($id_sector,$filtro=array('op','fecha')) {
        $query = "SELECT
                d.id,

                smt.modelo,
                smt.lote,
                smt.panel,

                d.cantidad,
                d.fecha,
                d.hora,
                d.id_turno,
                d.id_sector,
                d.id_destino,
                (
                  select turno from placas_dev.turno where id = d.id_turno
                ) as turno,
                (
                  select sector from placas_dev.sector where id = d.id_sector
                ) as sector,
                (
                  select sector from placas_dev.sector where id = d.id_destino
                ) as destino,
                d.op,
                d.stocker,
                d.semielaborado,
                (
                    SELECT SUM(cantidad) from placas_dev.datos dd where dd.op = d.op
                ) as salidas,
                smt.prod_aoi,
                smt.prod_man,
                smt.qty

                FROM
                placas_dev.datos d

                left join (
                    select  *
                    from smtdatabase.orden_trabajo
                ) as smt
                on smt.op = d.op

                 where
                  d.id_sector = ".$id_sector."

        ";

        $filtro = (object) $filtro;

        if($filtro->fecha!='all')
        {
            if($filtro->fecha=='now') { $query .= ' and d.fecha = CURDATE()'; } else {
                $query .= ' and d.fecha = "'.$filtro->fecha.'"';
            }
        }

        if(!empty($filtro->op)) { $query .= ' and d.op = "'.$filtro->op.'"'; }

        $query .= ' order by d.fecha desc, d.hora desc';

        $sql = DB::connection('iaserver')->select($query);

        if(!$sql) {
            $sql = array("error" => "Sin datos");
        }

        return $sql;
    }

    public static function salidaByOp($op) {
        $sql = Datos::where('op',$op)->sum('cantidad');

        if(!is_numeric($sql)) {
            $sql = array("error" => "Sin datos");
        }

        return $sql;
    }
}
