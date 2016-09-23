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

    public function panelEnTransaccionesWipOrCheckInterfazWip($panel)
    {
        $jbloques = clone $panel;
        foreach($jbloques->joinBloques as $bloque)
        {
            $verify = new VerificarDeclaracion();
            $interfaz = $verify->bloqueEnTransaccionWip($bloque->barcode);

            $addBloque = new \stdClass();
            $addBloque->bloque = $bloque;
            if(isset($interfaz->twip))
            {
                $addBloque->declaracion = $interfaz->declaracion;
                $addBloque->twip = $interfaz->twip;

                if($interfaz->twip->trans_ok != 1)
                {
                    $retryVerify = new VerificarDeclaracion();
                    $retryInterfaz = $retryVerify->bloqueEnInterfazWip($bloque->barcode,$panel->inspected_op);
                    $addBloque->declaracion = $retryInterfaz->declaracion;
                    $addBloque->wip = $retryInterfaz->wip;

                    // Si existe registro Wip, lo replico en TransaccionesWip
                    if(isset($retryInterfaz->wip->id) && $retryInterfaz->wip->trans_ok > 0)
                    {
                        $interfaz->twip->trans_ok = $retryInterfaz->wip->trans_ok;
                        $interfaz->twip->save();
                    }
                }
            } else
            {
                $verify = new VerificarDeclaracion();
                $interfaz = $verify->bloqueEnInterfazWip($bloque->barcode,$panel->inspected_op);
                $addBloque->declaracion = $interfaz->declaracion;
                $addBloque->wip = $interfaz->wip;

                // Si existe registro Wip, lo replico en TransaccionesWip
                if(isset($interfaz->wip->id))
                {
                    $twip = new TransaccionWip();
                    $twip->barcode = $bloque->barcode;
                    $twip->trans_id = $interfaz->wip->id;
                    $twip->trans_ok = $interfaz->wip->trans_ok;
                    $twip->trans_det = null;
                    $twip->id_panel = $panel->id;
                    $twip->save();
                }
            }

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

    public function bloqueEnTransaccionWip($barcode)
    {
        $twip = TransaccionWip::where('barcode',$barcode)->orderBy('created_at','desc')->first();
        if($twip != null) {
            $this->declaracion->declarado_total = ($twip->trans_ok == 1) ? 1 : 0;
            $this->declaracion->pendiente_total = ($twip->trans_ok == 0) ? 1 : 0;
            $this->declaracion->error_total = ($twip->trans_ok != 0 && $twip->trans_ok != 1 ) ? 1 : 0;

            $this->declaracion->process(1);

            $this->twip = $twip;
        }
        return $this;
    }
}