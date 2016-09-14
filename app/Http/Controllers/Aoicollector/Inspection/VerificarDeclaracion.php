<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip;
use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\StockerContentDeclaracion;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class VerificarDeclaracion extends Controller
{
    public $declaracion = null;
    public $bloques = [];

    public function __construct()
    {
        $this->declaracion = new StockerContentDeclaracion();
    }

    public function panelSecundarioEnInterfazWip($panel)
    {
        $w = new Wip();
        $wip = $w->findBarcodeSecundario($panel->panel_barcode, $panel->inspected_op);

        if(count($wip)>0)
        {
            $this->declaracion->declarado_total = $wip->where('trans_ok','1')->count();
            $this->declaracion->pendiente_total = $wip->where('trans_ok','0')->count();
            $this->declaracion->error_total = $wip->where('trans_ok','<>','0')->count() + $wip->where('trans_ok','<>','1')->count();

            $this->declaracion->process($panel->bloques);
            //$this->wip = $wip;
        }

        return $this;
    }

    public function panelEnInterfazWip($panel)
    {
        $jbloques = clone $panel;
        foreach($jbloques->joinBloques as $bloque)
        {
            $verify = new VerificarDeclaracion();
            $interfaz = $verify->bloqueEnInterfazWip($bloque->barcode,$panel->inspected_op);

            $addBloque = new \stdClass();
            $addBloque->bloque = $bloque;
            $addBloque->declaracion = $interfaz->declaracion;
            $addBloque->wip = $interfaz->wip;

            $this->bloques[] = $addBloque;
        }

        $this->declaracion->declarado_total = collect($this->bloques)->sum('declaracion.declarado_total');
        $this->declaracion->pendiente_total = collect($this->bloques)->sum('declaracion.pendiente_total');
        $this->declaracion->error_total = collect($this->bloques)->sum('declaracion.error_total');

        $this->declaracion->process($panel->bloques);

        return $this;
    }

    public function bloqueEnInterfazWip($barcode,$op)
    {
        $w = new Wip();
        $wip = $w->findBarcode($barcode, $op);

        if(count($wip)>0)
        {
            $this->declaracion->declarado_total = $wip->where('trans_ok','1')->count();
            $this->declaracion->pendiente_total = $wip->where('trans_ok','0')->count();
            $this->declaracion->error_total = $wip->where('trans_ok','<>','0')->count() + $wip->where('trans_ok','<>','1')->count();

            $this->declaracion->process(1);
        }

        $this->wip = $wip->first();

        return $this;
    }

    public function bloqueEnTransaccionWip($barcode) {

        $twip = TransaccionWip::where('barcode',$barcode)->orderBy('created_at','desc')->first();

        if($twip != null) {
            $twip->declarado = false;
            $twip->pendiente = false;
            $twip->error = false;

            if ($twip->trans_ok == 1) {
                $twip->declarado = true;
            }

            if ($twip->trans_ok == 0) {
                $twip->pendiente = true;
            }

            if ($twip->trans_ok > 1) {
                $twip->error = true;
            }

            $this->declarado = ($twip->declarado==null) ? false : $twip->declarado;
            $this->pendiente = ($twip->pendiente==null) ? false : $twip->pendiente;
            $this->error = ($twip->error==null) ? false : $twip->error;
            $this->last = $twip;
        }

        return $this;
    }
}