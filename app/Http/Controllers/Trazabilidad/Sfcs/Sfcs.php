<?php
namespace IAServer\Http\Controllers\Trazabilidad\Sfcs;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Sfcs extends Controller
{
    public static function insertTrazaAoi($barcode, $op, $config_linea,$puesto_id, $linea) {
        $query = "
         INSERT INTO
             [sfcsplus].[dbo].[TRAZA_AOI]
             (
                 [Codigo]
                ,[OP_NRO]
                ,[Configlinea_id]
                ,[Puesto_id]
                ,[Linea]
                ,[Fecha_insercion]
             )
              VALUES
            (
            '".$barcode."'
            ,'".$op."'
            ,'".$config_linea."'
            ,'".$puesto_id."'
            ,'".$linea."'
            , CURRENT_TIMESTAMP
           );
       ";

        $sql = DB::connection('sfcs_aoi')->sta_aoitement($query);
        if($sql)
        {
            $sql = DB::connection('sfcs_aoi')->select("SELECT @@IDENTITY as lastid");
        }
        return $sql;
    }

    public function modeloRelacionEbs($modelo_id)
    {
        $sql = DB::connection('sfcs')->table("sfcsplus.dbo.modelorelacionebs")->where('modelo_id', $modelo_id)->get();
        return $sql;
    }

    public function declareMode($op,$linea_id,$modelo_id,$puesto_id)
    {
        $sql = DB::connection('sfcs')->select("
        SELECT
        cp.RegEx  as regex,
        op.Numero as op,
        cl.Id as line_id,
        pu.Nombre as puesto,
        pu.Id as puesto_id,
        cl.Modelo_id as modelo_id,
        pu.Declara as declara
        FROM
            sfcsplus.dbo.ordenproduccion as op,
            sfcsplus.dbo.configlinea as cl,
            sfcsplus.dbo.puesto as pu,
            sfcsplus.dbo.CodigoPuesto cp
        WHERE
            cl.Modelo_id = ".$modelo_id." AND
            cl.Id = ".$linea_id." AND
            pu.Id = ".$puesto_id." AND
            op.Numero= '".$op."' AND
            cp.Puesto_id = ".$puesto_id."
        ");

        return head($sql);
    }

    public function puestosOp($op)
    {
        $sql = DB::connection('sfcs')->select("
        SELECT
        op.Numero as op,
        cl.Id as line_id,
        pu.Nombre as puesto,
        pu.Id as puesto_id,
        cl.Modelo_id as modelo_id,
        pu.Declara as declara
        FROM
            sfcsplus.dbo.ordenproduccion as op,
            sfcsplus.dbo.configlinea as cl,
            sfcsplus.dbo.puesto as pu
        WHERE
            cl.Modelo_id = op.Modelo_id AND
            cl.Id = pu.ConfigLinea_id AND
            op.Numero= '".$op."'
        ");

        $rta = array(
            'line_id' =>0,
            'modelo_id' =>0,
            'list' =>array(),
        );

        foreach($sql as $r) {
            $rta['line_id'] = (int)$r->line_id;
            $rta['modelo_id'] = (int)$r->modelo_id;
            $rta['list'][] = array(
                'puesto' => $r->puesto,
                'puesto_id' => (int)$r->puesto_id,
                'declara' => (int)$r->declara,
            );
        }

        return $rta;
    }

    public function configlinea($lineId)
    {
        $query = "SELECT
           CP.RegEx  as regex,
           M.Id as modelo_id,
           CL.linea_id,
           CL.sector_id,
           CP.Id as codigo_puesto_id,
           isNull(P.Declara,0) as puesto_declara,
           isNull(CV.DeclaraProduccion,0) as linea_declara

           FROM sfcsplus.dbo.ConfigLinea CL
           inner join sfcsplus.dbo.Modelo M on M.Id = CL.Modelo_id
           inner join sfcsplus.dbo.Puesto P on P.ConfigLinea_id = CL.id
           inner join sfcsplus.dbo.ConfigValidaciones CV on CV.ConfigLinea_id = CL.id
           inner join sfcsplus.dbo.CodigoPuesto CP on CP.Puesto_id = P.Id

           WHERE
           CL.Id = ".$lineId;

        $sql = DB::connection('sfcs')->select($query);
        return $sql;
    }
}
