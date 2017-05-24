<?php

namespace IAServer\Http\Controllers\Aoicollector\Cuarentena;

use IAServer\Http\Controllers\Aoicollector\Model\Cuarentena;
use IAServer\Http\Controllers\Aoicollector\Model\CuarentenaDetalle;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CuarentenaController extends Controller
{
    public function getDetail($barcode) {
        $detail = CuarentenaDetalle::where('barcode',$barcode)
            ->orderBy('updated_at','desc')
            ->first();

        $ob = new \stdClass();
        $ob->isBlocked = false;
        $ob->detalle = $detail;
        $ob->causa = null;

        if(isset($detail)) {
            $ob->causa = $detail->joinCuarentena;

            if($detail->released_at==null) {
                $ob->isBlocked = true;
            } else {
                $ob->isBlocked = false;
            }
        } else {
            $ob->isBlocked = false;
        }

        return $ob;
    }

    public function detailJoinedWithBlocks(Cuarentena $cuarentena)
    {
        $q = PanelHistory::select(DB::raw("
            hb.barcode,
            hp.turno,
            hb.revision_aoi,
            hb.revision_ins,
            hb.errores,
            hb.falsos,
            hb.reales,
            hp.inspected_op,
            p.semielaborado,
            hp.created_date,
            hp.created_time,
            (
                select trans_ok from `aoidata`.`transaccion_wip` as subt where
                subt.barcode = hb.barcode
                order by subt.created_at  desc limit 1
            ) as trans_ok,
            (
                select stkr.name from `aoidata`.`transaccion_wip` as subt
                inner join aoidata.stocker_route stkr on stkr.id = subt.id_last_route
                where
                subt.barcode = hb.barcode
                order by subt.created_at desc limit 1
            ) as ultima_ruta,
            (
                select stk.barcode from `aoidata`.`stocker_detalle` as stkd
                inner join aoidata.stocker stk on stk.id = stkd.id_stocker
                where
                stkd.id_panel = p.id
                limit 1
            ) as stocker,
            cuad.created_at as cuarentena_ini_at,
            cuad.released_at as cuarentena_end_at,
            IF(cuad.released_at is null, 'cuarentena','released') as estado,

            SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (60*60)) * (60*60)) AS periodo
	        "))
            ->from("aoidata.history_inspeccion_bloque as hb")
            ->join('aoidata.history_inspeccion_panel as hp', DB::raw('hb.id_panel_history'), '=', DB::raw('hp.id_panel_history'))
            ->join('aoidata.inspeccion_panel as p', DB::raw('hp.id_panel_history'), '=', DB::raw('p.first_history_inspeccion_panel'))
            ->join('aoidata.cuarentena_detalle as cuad', DB::raw('cuad.barcode'), '=', DB::raw('hb.barcode'))

            ->where(DB::raw('cuad.id_cuarentena'),$cuarentena->id)

            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

        return $q->get();
    }
}