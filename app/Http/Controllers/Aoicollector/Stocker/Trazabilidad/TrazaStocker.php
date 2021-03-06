<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad;

use IAServer\Http\Controllers\Aoicollector\Cuarentena\CuarentenaController;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\StockerController;
use IAServer\Http\Controllers\Aoicollector\Stocker\Src\StockerContent;
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
    public function findStocker($barcode,$withTraza=true)
    {
        $barcode = strtoupper($barcode);
        $output = array();
        if($this->isValidStockerBarcode($barcode)) {
            $stocker = $this->stockerInfoByBarcode($barcode);
            if (isset($stocker->error)) {
                $error = $stocker->error;
                $output = compact('error');
            } else {
                if($withTraza) {
                    $trazabilidad = $this->getStockerTraza($stocker->id);
                }

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

    public function stockerWithCarentena(StockerContent $content)
    {
        $cuarentena = [];

        foreach($content->paneles as $itemPanel)
        {
            if($itemPanel->panel->isSecundario())
            {
                $check = new CuarentenaController();
                $detail = $check->getDetail($itemPanel->panel->panel_barcode);

                if($detail->isBlocked) {
                    $cuarentena[] = $detail;
                }
            } else {
                foreach($itemPanel->bloques as $itemBloque)
                {
                    $check = new CuarentenaController();
                    $detail = $check->getDetail($itemBloque->bloque->barcode);

                    if($detail->isBlocked) {
                        $cuarentena[] = $detail;
                    }
                }
            }
        }

        return $cuarentena;
    }

    public function stockerDeclaredDetail(Stocker $stocker)
    {
        $content = $this->getStockerContent($stocker->id);

        $o = new StockerContent();

        foreach($content as $stkdet)
        {
            $find = new FindInspection();
            $find->onlyLast = true;

            $result = $find->barcode($stkdet->joinPanel->panel_barcode);

            $panel = $result->last->panel;

            $addPanel = new \stdClass();
            $addPanel->panel = $panel;

            if($panel->isSecundario())
            {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);

                $addPanel->declaracion = $interfazWip->declaracion;
                $addPanel->bloques = $interfazWip->bloques;
            } else
            {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelEnTransaccionesWipOrCheckInterfazWip($panel);

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

    public function withOp($op)
    {
         $allstocker = Stocker::vista()
            ->where('op', $op)
            ->where('paneles','>',0)
            ->where('id_stocker_route', 1)
            ->orderBy('created_at','desc')
            ->get();

        /*
                $stockerList = [];
                if (count($allstocker) > 0) {
                    foreach ($allstocker as $stk) {
                        $stockerList[] = $this->stockerInfoById($stk->id);
                    }
                }*/

        return $allstocker;
    }

    public function rastrearOp($op="")
    {
        $op = strtoupper( $op);
        if(empty($op))
        {
            $op = strtoupper( Input::get('rastrearop') );
        }

        if(!empty($op))
        {
            $allstocker = Stocker::vista()
                ->where('op', $op)
                ->where('paneles','>',0)
                ->whereRaw('(`id_stocker_route` =  1  )')
               // ->whereRaw('(`id_stocker_route` =  1 or `id_stocker_route` =  2 )')
                ->orderBy('created_at','desc')
                ->get();
//                ->paginate(20);
        }

        if (count($allstocker) > 0) {
            foreach ($allstocker as $stk) {

                $verify = new VerificarDeclaracion();
                $stk->transaccion = collect($verify->transaccionWipStatusByStocker($stk));
                $stk->declarado_total = $stk->transaccion->where('trans_ok',1)->count();
                $stk->pendiente_total = $stk->transaccion->where('trans_ok',0)->count();

                $errores = $stk->transaccion->filter(function ($item, $index) {
                    return ((int) $item->trans_ok > 1) ? true : false;
                });

                $stk->error_total = 0;
                if(count($errores)>0)
                {
                    $stk->error_total = count($errores);
                }
            }
        }

        $output = compact('op','allstocker');

        return $output;
    }
}
