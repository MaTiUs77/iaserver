<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.inspeccion_panel';

    public function scopeBuscarPanel($query, $barcode)
    {
        return $this->where('panel_barcode',$barcode)
            ->leftJoin('aoidata.maquina', 'maquina.id','=','id_maquina')
            ->select(['inspeccion_panel.*','maquina.linea'])
            ->get();
    }

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
}
