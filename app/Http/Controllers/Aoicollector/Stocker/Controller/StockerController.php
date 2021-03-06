<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker\Controller;

use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle;
use IAServer\Http\Controllers\Aoicollector\Model\StockerTraza;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class StockerController extends Controller
{
    public $stockerBarcodePattern = '/^STK[0-9]{5}$/';

    public function isValidStockerBarcode($stockerBarcode)
    {
        if (preg_match($this->stockerBarcodePattern, $stockerBarcode))
        {
            return true;
        } else {
            return false;
        }
    }

    //////////////////////////////////////////////////////////////////
    // GET STOCKER INFO
    //////////////////////////////////////////////////////////////////
    public function stockerInfoByBarcode($stockerBarcode)
    {
        $output = null;

        $stockerBarcode= strtoupper($stockerBarcode);
        if($this->isValidStockerBarcode($stockerBarcode))
        {
            $info = $this->getStockerInfo(null,$stockerBarcode);
            if ($info->error) {
                $output = (object)array('error'=> "El codigo del stocker solicitado, no existe.");
            } else
            {
                $output = $info;
            }
        } else {
            $output = (object)array('error'=> "El codigo del stocker no es valido.", 'code'=>405);
        }

        return $output;
    }

    public function stockerInfoById($idStocker)
    {
        $output = null;

        if(is_numeric($idStocker)) {
            $info = $this->getStockerInfo($idStocker);

            if ($info->error) {
                $output = (object)array('error'=> "El id del stocker solicitado, no existe.");
            } else
            {
                $output = $info;
            }
        } else {
            $output = (object)array('error'=> "No se definio id de stocker.");
        }

        return $output;
    }

    public function getStockerInfo($idStocker=null,$stockerBarcode=null)
    {
        $stocker = null;
        if($stockerBarcode==null)
        {
            $stocker = Stocker::findByIdStocker($idStocker);
        } else {
            $stocker = Stocker::findByStockerBarcode($stockerBarcode);
        }

        if(isset($stocker->id))
        {
            $stocker->unidades = $stocker->paneles * $stocker->bloques;
            if( ($stocker->paneles > 0) && ($stocker->paneles==$stocker->limite) ) { $stocker->full = 1;	} else { $stocker->full = 0; }

            $output = $stocker;

            return $output;
        } else
        {
            return (object )array('error'=>'El codigo de stocker no existe','code'=>404);
        }
    }

    public function getStockerContent($idStocker)
    {
        $stocker = StockerDetalle::where('id_stocker',$idStocker)->get();
        return $stocker;
    }

    public function getStockerTraza($idStocker)
    {
        $stocker = StockerTraza::where('id_stocker',$idStocker)
            ->orderBy('created_at','desc')
            ->take(5)
            ->get();
        return $stocker;
    }
    //////////////////////////////////////////////////////////////////

    public function changeOp($stockerBarcode,$toOp)
    {
        $stocker = Stocker::where('barcode',$stockerBarcode)->first();
        $content = $this->getStockerContent($stocker->id);

        $fromOp = $stocker->op;
        $stocker->op = $toOp;

        foreach($content as $stkDetalle)
        {
            $panel = $stkDetalle->joinPanel;
            $history = PanelHistory::where('panel_barcode',$panel->panel_barcode)->get();


            foreach($history as $his)
            {
                $his->inspected_op = $toOp;
                $his->save();
            }

            $panel->inspected_op = $toOp;
            $panel->save();
        }

        $stocker->save();

    }

    public function stockerControldeplacas($stockerBarcode)
    {
        $output = null;
        $stocker = $this->stockerInfoByBarcode($stockerBarcode);
        if(isset($stocker->error)) {
            $output = $stocker;
        } else {
            if (isset($stocker->id)) {
                $stocker->sendToRouteId(2);
                $stocker = Stocker::findByIdStocker($stocker->id);
                $output = compact('stocker');
            }
        }

        return $output;
    }

    public function addStocker(Produccion $produccion, $stockerBarcode)
    {
        $output = null;
        $stocker= $this->stockerInfoByBarcode($stockerBarcode);

        if(isset($stocker->error) && $stocker->code!=404) {
            $output = $stocker;
        } else {
            if (isset($stocker->id)) {
                if($stocker->despachado==1)
                {
                    $output= (object) array('error'=>"El stocker se registra como despachado con (" . $stocker->op . "), puede liberar el stocker si este se encuentra vacio");
                } else
                {
                    if ($stocker->full) {
                        $output= (object) array('error'=>"No es posible asignar este stocker, se encuentra full (" . $stocker->paneles . " de " . $stocker->limite . ") con (" . $stocker->op . ")");
                    } else {

                        $response = $this->sp_stockerSet($produccion, $stocker);

                        if(isset($response->error)) {
                            $output = $response;
                        } else {
                            $output = $this->stockerInfoById($stocker->id);
                        }
                    }
                }
            } else {
                // EL STOCKER NO EXISTE, SE CREA..
                $stocker = new Stocker();
                $stocker->barcode = $stockerBarcode;
                $stocker->limite = '0';

                $response = $this->sp_stockerSet($produccion, $stocker);
                $output = head($response);
            }
        }
        return $output;
    }

    public function removeStocker($stockerIdOrBarcode)
    {
        $output = null;
        $stocker = null;
        if(is_numeric($stockerIdOrBarcode))  {
            $stocker = $this->stockerInfoById($stockerIdOrBarcode);

        } else  {
            $stocker = $this->stockerInfoByBarcode($stockerIdOrBarcode);
        }
        if(isset($stocker->error)) {
            $output = $stocker;
        } else {
            if (isset($stocker->id)) {
                $response = Stocker::sp_stockerReset($stocker);
                $output = head($response);
            }
        }

        return $output;
    }

    public function configStocker($stockerBarcode,$limite,$bloques) {
        $output = null;
        if($limite>0 && $bloques>0) {
            $stocker = $this->stockerInfoByBarcode($stockerBarcode);
            if(isset($stocker->error)) {
                $output = $stocker;
            } else {
                if (isset($stocker->id)) {

                    $stk = Stocker::find($stocker->id);
                    $stk->limite =  $limite;
                    $stk->bloques =  $bloques;
                    $stk->save();

                    // Obtengo datos actualizados de DB
                    $stocker = $this->stockerInfoByBarcode($stockerBarcode);
                }
            }
            $output = compact('stocker');
        } else {
            $output = ['error'=>'Datos invalidos'];
        }

        return $output;
    }

    private function sp_stockerSet(Produccion $produccion, $stocker)
    {
        $procced = true;
        $output = null;

        // Si el stocker existe, verifica que la OP solicitada, corresponda a la actual
        if(isset($stocker->id))  {
            if(isset($stocker->op) && $stocker->op != '' && $stocker->op != $produccion->op) {
                $output = array('error'=>'El stocker actual contiene ('.$stocker->op.'), no corresponde a la solicitada ('.$produccion->op.'). Para realizar esta operacion, debe usar la misma OP, o liberar el stocker.');
                $procced = false;
            }
        }

        // Obtiene datos de semielaborado desde trazabilidad para relacionar al stocker
        $semielaborado = null;

        if(isset($produccion->op))
        {
            //if((!isset($stocker->limite) || $stocker->limite<=0))
            //{
                $w = new Wip();

                $wip = $w->findOp(str_replace('-B','',$produccion->op),false);

                $semielaborado = $wip->wip_ot->codigo_producto;

                $stocker->fill([
                    'semielaborado'=>$semielaborado
                ]);

                $lastStocker = Stocker::where('op',$produccion->op)
                    ->orderBy('updated_at','desc')
                    ->first();

                if(isset($lastStocker->limite) && $lastStocker->limite > 0) {
                    $stocker->bloques = $lastStocker->bloques;
                    $stocker->limite = $lastStocker->limite;
                }
            //}
        } else
        {
            $output = array('error'=>'No se definio OP de produccion.');
            $procced = false;
        }

        if($procced)
        {
            // Antes de asignar, libero cualquier relacion de la AOI con algun stocker.
            Stocker::changeProductionStocker($produccion->barcode);
            // Inserta o actualiza el codigo del stocker, con OP, LIMITE, BLOQUES. Y lo relaciona a la AOI.
            // Por defecto los bloques son CERO (0) hasta que se escanee la primer placa
            $output = Stocker::sp_stockerSet($produccion, $stocker,$semielaborado);
        }

        if(is_array($output))
        {
            $output = (object) $output;
        }
        return $output;
    }
}
