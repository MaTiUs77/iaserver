<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Panel extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.inspeccion_panel';

    public function scopeBuscarPanel($query, $barcode)
    {
        return $this->where('panel_barcode',$barcode)
            ->leftJoin('aoidata.maquina', 'maquina.id','=','id_maquina')
            ->select(['inspeccion_panel.*','maquina.linea'])
            ->get();
    }

    /**
     * Hace un join de la ultima inspeccion del panel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function joinBloques()
    {
        return $this->hasMany('IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory', 'id_panel_history', 'last_history_inspeccion_panel');
    }

    public function cogiscan()
    {
        $cogiscanService= new Cogiscan();
        return $cogiscanService->queryItem($this->panel_barcode);
    }

    public function smt()
    {
        $w = new Wip();
        $smt = SMTDatabase::findOp($this->inspected_op);

        // Obtengo semielaborado desde interfaz
        $wipResult = $w->findOp($this->inspected_op,false,false);
        $semielaborado =null;
        if(isset($wipResult->wip_ot->codigo_producto))
        {
            $semielaborado = $wipResult->wip_ot->codigo_producto;
        }
        $smt->semielaborado = $semielaborado;

        unset($smt->op);
        unset($smt->id);
        unset($smt->prod_aoi);
        unset($smt->prod_man);
        unset($smt->qty);

        return $smt;
    }

    function isSecundario()
    {
        if($this->bloques == $this->joinBloques()->where('etiqueta','V')->count()) {
            return true;
        } else
        {
            return false;
        }
    }

    public function twip()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip', 'id_panel', 'id');
    }

    public static function sinDeclarar($op) {
        $query = "
        select
			stk.barcode as stocker_barcode,
            tw.trans_ok,
            tw.trans_det,
            stkroute.name as stocker_route,
            proute.name as panel_route,
            hp.*
         from `aoidata`.`history_inspeccion_panel` as `hp`
         inner join `aoidata`.`inspeccion_panel` as `p` on hp.id_panel_history = p.last_history_inspeccion_panel
	     left join `aoidata`.`transaccion_wip` as tw on tw.barcode = hp.panel_barcode
         left join `aoidata`.`stocker_detalle` as stkd on stkd.id_panel = p.id
         left join `aoidata`.`stocker` as stk on stk.id = stkd.id_stocker
         left join `aoidata`.`stocker_traza` as stkt on stkt.id_stocker = stk.id and stkt.id = (SELECT MAX(substkt.id) FROM aoidata.stocker_traza substkt where substkt.id_stocker =  stkd.id_stocker)
         left join `aoidata`.`stocker_route` as stkroute on stkroute.id = stkt.id_stocker_route
         left join `aoidata`.`stocker_route` as proute on proute.id = tw.id_last_route

		 where

         hp.inspected_op = '$op'

         and not exists (
                select stw.trans_ok from `aoidata`.`transaccion_wip` as stw where
                stw.barcode = hp.panel_barcode and
                stw.trans_ok = 1
            )

         order by hp.created_date asc, hp.created_time asc";

        return DB::connection('aoidata')->select($query);
    }
}
