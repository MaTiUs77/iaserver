<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker\Panel;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\StockerController;
use IAServer\Http\Requests;

class PanelWithAoi extends StockerController
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
