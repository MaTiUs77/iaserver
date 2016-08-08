<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker\Controller;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle;
use IAServer\Http\Controllers\Aoicollector\Service\Service;
use IAServer\Http\Requests;

class PanelStockerController extends StockerController
{
    public function removePanel($panelBarcode)
    {
        $webservice = new Service();
        $service = (object) $webservice->barcodeStatus($panelBarcode);
        $panel = null;
        $output = array();


        if(isset($service->aoi->panel)) {
            $panel = $service->aoi->panel;
            $stocker = StockerDetalle::where('id_panel',$panel->id)->first();
            if(isset($stocker->id))
            {
                $stocker->delete();
                $output = array('done'=>'Panel removido');
            } else
            {
                $output = array('error'=>'El panel no fue previamente agregado a un stocker');
            }
        } else
        {
            $output = array('error'=>'El panel solicitado no existe en la base de datos');
        }

        if(is_array($output))  { $output = (object) $output; }
        return $output;
    }

    public function addPanel($panelBarcode,$aoibarcode)
    {
        $output = array();
        $stocker = null;

        $produccion = Produccion::barcode($aoibarcode);
        if(isset($produccion->id_stocker))
        {
            $stocker = $this->stockerInfoById($produccion->id_stocker);

            $webservice = new Service();
            $service = (object) $webservice->barcodeStatus($panelBarcode);
            $panel = null;

            if(isset($service->aoi->panel))
            {
                $panel = $service->aoi->panel;
                if(isset($stocker->op) && isset($panel->inspected_op)) {
                    if($stocker->op == $panel->inspected_op)
                    {
                        if($panel->revision_ins == 'OK')
                        {
                            if($service->aoi->analisis->despachar)
                            {
                                $output = $this->handle_stockerAddPanel($panel,$stocker);
                            } else
                            {
                                $output = array('error'=>'No se leyeron correctamente las etiquetas del panel, es necesaria una nueva inspeccion');
                            }
                        }
                    } else {
                        $output = array('error'=>'El panel solicitado tiene '.$panel->inspected_op.', no es igual'.$stocker->op.' configurado en el stocker');
                    }
                }
            } else {
                $output = array('error'=>'No se localizo el codigo del panel '.$panelBarcode.' en la base de datos');
            }
        } else {
            $output = array('error'=>'No hay Stocker definido en produccion');
        }

        if(is_array($output))  { $output = (object) $output; }

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
