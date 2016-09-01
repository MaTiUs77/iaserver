<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

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

    public function wip()
    {
        $w = new Wip();
        $wip = $w->findBarcode($this->panel_barcode, $this->inspected_op);

        $declarado = false;
        $pendiente = false;

        if(count($wip)>0)
        {

            if($wip->where('ebs_error_trans',null)->where('trans_ok','1')->count()>0)
            {
                $declarado = true;
            }

            if($wip->where('ebs_error_trans',null)->where('trans_ok','0')->count()>0)
            {
                $pendiente = true;
            }
        }

        $output = array();
        $output['declarado'] = $declarado;
        $output['pendiente'] = $pendiente;
        $output['last'] = $wip->first();
        $output['historial'] = $wip;

        return (object) $output;
    }

    public function wipSecundario()
    {
        $w = new Wip();
        $like = $this->panel_barcode.'-%';

        $wip = $w->findBarcode($this->panel_barcode, $this->inspected_op,"",$like);

        $declarado = false;
        $pendiente = false;

        if(count($wip)>0)
        {

            if($wip->where('ebs_error_trans',null)->where('trans_ok','1')->count()>0)
            {
                $declarado = true;
            }

            if($wip->where('ebs_error_trans',null)->where('trans_ok','0')->count()>0)
            {
                $pendiente = true;
            }
        }

        $output = array();
        $output['declarado'] = $declarado;
        $output['pendiente'] = $pendiente;
        $output['last'] = $wip->first();
        $output['historial'] = $wip;


        return (object) $output;
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

    public function bloqueWip()
    {
        $arr = [];
        foreach($this->joinBloques as $bloqueHistory)
        {
            $arr[] = $bloqueHistory->wip($this->inspected_op);
        }
        return $arr;
    }
}
