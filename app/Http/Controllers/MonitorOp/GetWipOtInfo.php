<?php

namespace IAServer\Http\Controllers\MonitorOp;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\P2i\Model\Carga;

use IAServer\Http\Controllers\P2i\Model\General;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\XXEWipOt;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GetWipOtInfo extends Controller
{
    public function infoOp($modelo)
    {
        return XXEWipOt::select(DB::raw("
                OT.*,
                (
                    SELECT TOP(1) S.FECHA_INSERCION FROM XXE_WIP_ITF_SERIE S
                    WHERE
                    S.NRO_OP = OT.WIP_ENTITY_NAME AND
                    S.ORGANIZATION_CODE = 'UP3'
                    ORDER BY
                    S.FECHA_INSERCION DESC
                ) AS ULTIMO_SERIE,

                (
                    SELECT TOP(1) H.FECHA_INSERCION FROM XXE_WIP_ITF_SERIE_HISTORY H
                    WHERE
                    H.NRO_OP = OT.WIP_ENTITY_NAME AND
                    H.ORGANIZATION_CODE = 'UP3'
                    ORDER BY
                    H.FECHA_INSERCION DESC
                ) AS ULTIMO_HISTORY,
                CAST(OT.quantity_completed as INT) as completed
                "))
            ->from('XXE_WIP_OT AS OT')
            ->whereRaw("OT.ORGANIZATION_CODE =  'UP3' ")
            ->whereRaw("OT.DESCRIPTION like '%" . $modelo . "%' ")
            ->ORDERBY("completed", "DESC")
            //->limit(4)
            ->get();

    }

    public function infoOpInsaut()
    {
//        if (isset($op)) {
//
//            return $op_modelo = XXE_WIP_OT::WHERE('XXE_WIP_OT.WIP_ENTITY_NAME', '=', '' . $op . '')
//                ->LEFTJOIN('XXE_WIP_ITF_SERIE', 'XXE_WIP_ITF_SERIE.NRO_OP', '=', 'XXE_WIP_OT.WIP_ENTITY_NAME')
//                // ->LEFTJOIN('XXE_WIP_ITF_SERIE_History','XXE_WIP_ITF_SERIE_History.NRO_OP','=','XXE_WIP_OT.WIP_ENTITY_NAME')
//                ->WHERE('XXE_WIP_OT.ORGANIZATION_CODE', 'UP3')
//                ->ORDERBY('XXE_WIP_ITF_SERIE.FECHA_INSERCION', 'DESC')
//                ->LIMIT(1)
//                ->GET();
//
//        } else {
        return $op_modelo = XXEWipOt::select(DB::raw("
                OT.*,
                (
                    SELECT TOP(1) S.FECHA_INSERCION FROM XXE_WIP_ITF_SERIE S
                    WHERE
                    S.NRO_OP = OT.WIP_ENTITY_NAME AND
                    S.ORGANIZATION_CODE = 'UP3'
                    ORDER BY
                    S.FECHA_INSERCION DESC
                ) AS ULTIMO_SERIE,

                (
                    SELECT TOP(1) H.FECHA_INSERCION FROM XXE_WIP_ITF_SERIE_HISTORY H
                    WHERE
                    H.NRO_OP = OT.WIP_ENTITY_NAME AND
                    H.ORGANIZATION_CODE = 'UP3'
                    ORDER BY
                    H.FECHA_INSERCION DESC
                ) AS ULTIMO_HISTORY,
                CAST(OT.quantity_completed as INT) as completed
                "))
            ->from('XXE_WIP_OT AS OT')
            ->whereRaw("OT.ORGANIZATION_CODE =  'UP3' ")
            ->whereRaw("OT.SEGMENT1 like '4-651%' ")
            ->ORDERBY("completed", "desc")
            //->limit(4)
            ->get();
        // }
    }

    public static function ultimaDeclaracion($fecha)
    {
        Carbon::setLocale('es');
        $datetime = Carbon::now();
        $dateSerieHistory = Carbon::createFromFormat('Y-m-d H:i:s.u', $fecha);
        $diferencia = $dateSerieHistory -> diffForHumans($datetime);
        return $diferencia;
    }

}
