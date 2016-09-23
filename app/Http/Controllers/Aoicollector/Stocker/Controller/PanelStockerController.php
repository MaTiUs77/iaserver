<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker\Controller;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle;
use IAServer\Http\Controllers\Aoicollector\Service\Service;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;

class PanelStockerController extends StockerController
{
    public function removePanel($panelBarcode)
    {
        $find = new FindInspection();
        $panel = (object) $find->barcode($panelBarcode);

        $output = array();

        if(!isset($panel->error)) {
            $panel = $panel->last->panel;
            $stockerDetalle = StockerDetalle::where('id_panel',$panel->id)->first();
            if(isset($stockerDetalle->id))
            {
                $stockerId = $stockerDetalle->id_stocker;
                $stockerDetalle->delete();
//                $output = array('done'=>'Panel removido');
                $output = $this->stockerInfoById($stockerId);
            } else
            {
                $output = array('error'=>'El panel no fue previamente agregado a un stocker');
            }
        } else
        {
            $output = $panel;
        }

        if(is_array($output))  { $output = (object) $output; }
        return $output;
    }

    public function declarePanel($panelBarcode)
    {
        $find = new FindInspection();
        $panel = (object) $find->barcode($panelBarcode);

        $output = array();

        if(!isset($panel->error)) {
            $panel = $panel->last->panel;
            $bloques = $panel->joinBloques;
            $w = new Wip();
            $opinfo = $w->otInfo($panel->inspected_op);

            if(isset($opinfo))
            {
                foreach ($bloques as $bloque) {
                    $output[] = $w->declarar('UP3', $panel->inspected_op, $opinfo->codigo_producto,1,$bloque->barcode);
                }
            }
        } else
        {
            $output = $panel;
        }

        if(is_array($output))  { $output = (object) $output; }
        return $output;
    }

    public function addPanel($panelBarcode,$aoibarcode)
    {
        $output = array();
        $stocker = null;

        // Verifica que exista stocker en produccion
        $produccion = Produccion::barcode($aoibarcode);
        if(isset($produccion->id_stocker))
        {
            // Obtiene datos de stocker
            $stocker = $this->stockerInfoById($produccion->id_stocker);

            // Busca datos del panel
            $find = new FindInspection();
            $panelInfo = (object) $find->barcode($panelBarcode);

            if(!isset($panelInfo->error))
            {
                // Ultimos datos del panel, ya que puede haber sido inspeccionado multiples veces
                $panel = $panelInfo->last->panel;

                // Si la OP coincide con la OP del Stocker
                if(isset($stocker->op) && isset($panel->inspected_op)) {
                    if($stocker->op == $panel->inspected_op)
                    {
                        // Solo se aceptan paneles OK
                        if($panel->revision_ins == 'OK')
                        {
                            if($panelInfo->last->analisis->despachar)
                            {
                                $output = $this->handle_stockerAddPanel($panel,$stocker);
                            } else
                            {
                                $output = array('error'=>'No se leyeron correctamente las etiquetas del panel, es necesaria una nueva inspeccion');
                            }
                        } else
                        {
                            $output = array('error'=>'El panel se detecto (NG), no se permite el ingreso al stocker. Es requerida una nueva inspeccion.');
                        }
                    } else {
                        $output = array('error'=>'La '.$panel->inspected_op.' del panel no coincide con la '.$stocker->op.' del stocker');
                    }
                }
            } else {
                $output = $panelInfo;
            }
        } else {
            $output = array('error'=>'No hay Stocker definido en produccion');
        }

        return $output;
    }

    public function addPanelManual($panelBarcode,$aoibarcode)
    {
        $output = array();
        $stocker = null;

        $produccion = Produccion::barcode($aoibarcode);
        if(isset($produccion->id_stocker))
        {
            $stocker = $this->stockerInfoById($produccion->id_stocker);

            $webservice = new Service();
            $service = (object) $webservice->barcodeStatus($panelBarcode,true);
            $panel = null;

            $output = $service;

        } else {
            $output = array('error'=>'No hay Stocker definido en produccion');
        }

        if(is_array($output))  { $output = (object) $output; }

        return $output;
    }

    private function handle_stockerAddPanel($panel,Stocker $stocker)
    {
        $output = array();

        if($stocker->full)
        {
            $output = array('error'=>'El stocker se encuentra completo');
            Stocker::changeProductionStocker(null,$stocker->id);
        } else
        {
            if($stocker->limite==0)
            {
                // Stocker sin configuracion, establezco configuracion por defecto
                if($stocker->bloques==0)
                {
                    $updateStocker = Stocker::find($stocker->id);
                    $updateStocker->bloques = $panel->bloques;
                    $updateStocker->limite = 17;
                    $updateStocker->save();
                }
            }

            // Verifico si el panel fue asignado a algun stocker anteriormente
            $panelInStocker = StockerDetalle::where('id_panel',$panel->id)->first();
            if(isset($panelInStocker->id))
            {
                $output = array('error'=>'El panel solicitado ya fue asignado a un stocker');
            } else
            {
                StockerDetalle::firstOrCreate([
                    'id_stocker' => $stocker->id,
                    'id_panel' => $panel->id
                ]);

                // Actualizo datos de stocker, para verificar si se encuentra full o no
                $stockerUpdatedInfo = $this->stockerInfoById($stocker->id);
                if($stockerUpdatedInfo->full)
                {
                    // Stocker completo, lo remuevo de produccion
                    Stocker::changeProductionStocker(null,$stocker->id);
                }

                $output = $stockerUpdatedInfo;
            }
        }

        if(is_array($output))  { $output = (object) $output; }

        return $output;
    }
}
