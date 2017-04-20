<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker\Panel;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle;
use IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\StockerController;
use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;

class PanelWithAoiDeclare extends StockerController
{
    public function add($panelBarcode,Produccion $produccion)
    {
        $output = array();
        // Obtiene datos de stocker
        $stocker = $this->stockerInfoById($produccion->id_stocker);

        // Busca datos del panel
        $find = new FindInspection();
        $find->onlyLast = true;
        $panelInfo = (object)$find->barcode($panelBarcode);

        if (!isset($panelInfo->error)) {
            // Ultimos datos del panel, ya que puede haber sido inspeccionado multiples veces
            $panel = $panelInfo->last->panel;

            // Si la OP coincide con la OP del Stocker
            if (isset($stocker->op) && isset($panel->inspected_op)) {
                if ($stocker->op == $panel->inspected_op) {
                    // Solo se aceptan paneles OK
                    if ($panel->revision_ins == 'OK') {
                        if ($panelInfo->last->analisis->despachar)
                        {
                            $prodinfo = (object) Produccion::fullInfo($produccion->barcode,[
                                'smt'=>true,
                                'transaction'=>true,
                                'period' => false,
                                'sfcsroute' => false,
                                'stocker' => false
                            ]);

                            if(isset($prodinfo->produccion->wip->active)){
                                if($prodinfo->produccion->route->declare) {

                                    $bloques = $panel->joinBloques;
                                    $w = new Wip();

                                    if(count($bloques)) {
                                        foreach ($bloques as $bloque) {
                                            $declarado = $w->declared($bloque->barcode,$panel->inspected_op);

                                            if(isset($declarado->trans_ok) && ($declarado->trans_ok == 1 || $declarado->trans_ok == 0))
                                            {
                                                $output = ["error" => "La placa ya fue declarada"];
                                            } else
                                            {
                                                $output[] = $w->declarar('UP3', $panel->inspected_op, $prodinfo->produccion->wip->wip_ot->codigo_producto,1,$bloque->barcode);
                                            }
                                        }
                                    } else {
                                        $output = ["error" => "La placa no tiene bloques!, es necesaria una reinspeccion"];
                                    }
                                }
                            }

                            $panelStocker = new PanelStocker();
                            $output = $panelStocker ->add($panel,$stocker);
                        } else {
                            $output = array('error' => 'No se leyeron correctamente las etiquetas del panel, es necesaria una nueva inspeccion');
                        }
                    } else {
                        $output = array('error' => 'El panel se detecto (NG), no se permite el ingreso al stocker. Es requerida una nueva inspeccion.');
                    }
                } else {
                    $output = array('error' => 'La ' . $panel->inspected_op . ' del panel no coincide con la ' . $stocker->op . ' del stocker');
                }
            }
        } else {
            $output = (array) $panelInfo;
        }

        return  $output;
    }
}
