<?php
namespace IAServer\Http\Controllers\Trazabilidad;

use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle;
use IAServer\Http\Controllers\Aoicollector\Prod\Stocker\StockerController;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class TrazaStocker extends Controller
{
    public function findElement($element="")
    {
        $element = strtoupper( $element );
        if(empty($element))
        {
            $element = strtoupper( Input::get('element') );
        }

        $stockerCtrl = new StockerController();
        if($stockerCtrl->isValidStockerBarcode($element)) {
            return $this->findStocker($element);
        } else
        {
            return $this->locatePanelInStocker($element);
        }
    }

    public function findStocker($barcode)
    {
        $output = array();
        $stockerCtrl = new StockerController();
        if($stockerCtrl->isValidStockerBarcode($barcode)) {
            $stocker = $stockerCtrl->stockerInfoByBarcode($barcode);
            if (isset($stocker->error)) {
                $error = $stocker->error;
                $output = compact('error');
            } else {
                if(isset($stocker->aoi_barcode))
                {
                    $linea = Produccion::barcode($stocker->aoi_barcode)->linea;
                    $stockerDetalle = $stockerCtrl->getStockerContent($stocker->id);
                    $stockerTraza = $stockerCtrl->getStockerTraza($stocker->id);
                    $output = compact('linea','stocker', 'stockerDetalle','stockerTraza','mode');
                } else
                {
                    $error = "El stocker se encuentra en el limbo";
                    $output = compact('error');
                }
            }
            return view('trazabilidad.stocker.index', $output);
        }
    }

    public function locatePanelInStocker($panelBarcode)
    {
        $mode = 'panel';
        // Localizo panel
        $panelHistory = PanelHistory::buscar($panelBarcode);

        if($panelHistory==null)
        {
            $error = "El panel no fue localizado";
            $output = compact('error');
        } else
        {
            $panel = head(head($panelHistory));

            // Obtengo ID del Stocker en donde se encuentra ubicado el panel
            if(isset($panel->joinStockerDetalle))
            {
                $id_stocker = $panel->joinStockerDetalle->id_stocker;
                // Obtengo datos de Stocker
                $stockerCtrl = new StockerController();
                $stocker = $stockerCtrl->getStockerInfo($id_stocker);

                if (isset($stocker->error)) {
                    $error = $stocker->error;
                    $output = compact('error');
                } else {
                    if(isset($stocker->aoi_barcode))
                    {
                        $linea = Produccion::barcode($stocker->aoi_barcode)->linea;
                        $stockerDetalle = $stockerCtrl->getStockerContent($stocker->id);
                        $stockerTraza = $stockerCtrl->getStockerTraza($stocker->id);
                        $output = compact('linea','stocker', 'stockerDetalle','stockerTraza','panel');
                    } else
                    {
                        $error = "El stocker se encuentra en el limbo";
                        $output = compact('error');
                    }
                }
            } else
            {
                $error = "El panel no se encuentra ubicado en stocker";
                $output = compact('error');
            }
        }

        return view('trazabilidad.stocker.index', $output);
    }
}
