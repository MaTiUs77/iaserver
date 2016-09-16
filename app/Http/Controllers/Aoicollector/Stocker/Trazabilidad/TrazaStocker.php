<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\StockerController;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;

class TrazaStocker extends StockerController
{
    // Localiza un stocker o un panel segun el elemento enviado
    public function findElement($element="")
    {
        $element = strtoupper( $element );
        if(empty($element))
        {
            $element = strtoupper( Input::get('element') );
        }

        if($this->isValidStockerBarcode($element)) {
            return $this->findStocker($element);
        } else
        {
            return $this->locatePanelInStocker($element);
        }
    }

    // Localiza un stocker segun su barcode
    public function findStocker($barcode)
    {
        $barcode = strtoupper($barcode);
        $output = array();
        if($this->isValidStockerBarcode($barcode)) {
            $stocker = $this->stockerInfoByBarcode($barcode);
            if (isset($stocker->error)) {
                $error = $stocker->error;
                $output = compact('error');
            } else {
                $trazabilidad = $this->getStockerTraza($stocker->id);

                if(isset($stocker->aoi_barcode))
                {
                    $linea = Produccion::barcode($stocker->aoi_barcode)->linea;
                }

                $output = compact('linea','stocker','trazabilidad');
            }
        } else
        {
            $error = "El stocker no existe";
            $output = compact('error');
        }

        return (object) $output;
    }

    public function stockerDeclaredDetail(Stocker $stocker)
    {
        $content = $this->getStockerContent($stocker->id);

        $o = new StockerContent();

        foreach($content as $stkdet)
        {
            $fi = new FindInspection();
            $panel = $fi->barcode($stkdet->joinPanel->panel_barcode);
            $panel = $panel->last->panel;

            $addPanel = new \stdClass();
            $addPanel->panel = $panel;

            if($panel->isSecundario())
            {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);

                $addPanel->declaracion = $interfazWip->declaracion;
            } else
            {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelEnTransaccionesWipOrCheckInterfazWip($panel);
                //$interfazWip = $verify->panelEnInterfazWip($panel);

                $addPanel->declaracion = $interfazWip->declaracion;
                $addPanel->bloques = $interfazWip->bloques;
            }

            $o->paneles[] = $addPanel;
        }

        $o->process($stocker->unidades);

        return $o;
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
                $stocker = $this->getStockerInfo($id_stocker);

                if (isset($stocker->error)) {
                    $error = $stocker->error;
                    $output = compact('error');
                } else {
                    if(isset($stocker->aoi_barcode))
                    {
                        $linea = Produccion::barcode($stocker->aoi_barcode)->linea;
                        $stockerDetalle = $this->getStockerContent($stocker->id);
                        $stockerTraza = $this->getStockerTraza($stocker->id);
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

        return (object) $output;
    }
}
