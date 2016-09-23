<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory;
use IAServer\Http\Controllers\Aoicollector\Model\DetalleHistory;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class FindInspection extends Controller
{
    public $withWip = false;
    public $withDetail = false;
    public $withProductioninfo = false;
    public $withSmt = false;
    public $withCogiscan = false;
    public $onlyLast = false;

    public function barcode($barcode)
    {
        $db = 'current';

        // Si el barcode es valido, se realiza la busqueda en PanelHistory
        if(is_numeric($barcode)) {
            $panel = PanelHistory::buscar($barcode);

            // Si encontro resultados...
            if($panel!=null)
            {
                $result = new \stdClass();
                $result->last = $this->panelDataHandler($barcode,collect($panel)->first());

                if(count($panel)>1 && !$this->onlyLast )
                {
                    $result->historial = null;

                    foreach($panel as $historyPanel)
                    {
                        $result->historial[] = $this->panelDataHandler($barcode,$historyPanel);
                    }
                }

                return $result;
            } else
            {
                $error = "No se localizo el barcode en AOI";
                $output = compact('db','barcode', 'error');
            }

        } else
        {
            $error = "El dato es invalido, solo se permite barcode numerico.";
            $output = compact('db','barcode', 'error');
        }

        return $output;
    }

    private function panelDataHandler($barcode,  $panel)
    {
        $moreInfo = new \stdClass();
        $moreInfo->panel = $panel;
        $moreInfo->bloque = null;
        $moreInfo->detalle = null;
        $moreInfo->production = null;
        $moreInfo->smt = null;
        $moreInfo->analisis = null;

        if (isset($panel->panel_barcode))
        {
            $bloques = BloqueHistory::where('id_panel_history', $panel->id_panel_history)->get();

            $bloque = $bloques->where('barcode',$barcode)->first();

            $moreInfo->analisis = $this->analisisDespacho($bloques,$panel);
            if($moreInfo->analisis->mode == 'E')
            {
                $moreInfo->bloque = $bloque;
            }

            if ($this->withDetail) {
                if($bloque!=null)
                {
                    $moreInfo->detalle = DetalleHistory::fullDetail($bloque->id_bloque_history)->get();
                }
            }

            if ($this->withProductioninfo) {
                $moreInfo->production = Produccion::maquina($panel->id_maquina);
            }

            if($this->withSmt) {
                $moreInfo->smt = $panel->smt();
            }
            if($this->withCogiscan)
            {
                $moreInfo->cogiscan = $panel->cogiscan();
            }
            if($this->withWip)
            {
                $verify = new VerificarDeclaracion();
                $moreInfo->wip = $verify->bloqueEnInterfazWip($bloque->barcode,$panel->inspected_op);
            }

            return $moreInfo;

        } else {
            $error = "No se localizo el barcode en AOI";
            return compact('error');
        }
    }

    private function analisisDespacho($bloqueHistory, $panelHistory)
    {
        $info = new \stdClass();
        $info->despachar = false;
        $info->mode = 'U';

        $info->etiqueta_fisica = count(array_where($bloqueHistory, function ($key, $value) {
            if($value->etiqueta=='E'){
                return $value;
            }
        }));

        $info->etiqueta_virtual = count(array_where($bloqueHistory, function ($key, $value) {
            if($value->etiqueta=='V'){
                return $value;
            }
        }));

        if($info->etiqueta_fisica == $panelHistory->bloques) {
            $info->despachar = true;
            $info->mode = 'E';
        }

        if($info->etiqueta_virtual == $panelHistory->bloques) {
            $info->despachar = true;
            $info->mode = 'V';
        }

        if($panelHistory->revision_ins == 'OK' && $info->mode != 'U')
        {
            $info->despachar = true;
        }

        return $info;
    }
}