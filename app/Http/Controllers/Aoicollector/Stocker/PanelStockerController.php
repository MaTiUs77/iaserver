<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\StockerController;
use IAServer\Http\Controllers\Aoicollector\Stocker\Panel\PanelStocker;
use IAServer\Http\Controllers\Aoicollector\Stocker\Panel\PanelWithAoi;
use IAServer\Http\Controllers\Aoicollector\Stocker\Panel\PanelWithAoiDeclare;
use IAServer\Http\Controllers\Aoicollector\Stocker\Panel\PanelWithCogiscan;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;

/**
 * ABM para los paneles que se ingresan a un stocker, permite remover, forzar transaccion wip, declarar placas, y agregar placas a un stocker en diferentes modos
 *
 * @package IAServer\Http\Controllers\Aoicollector\Stocker
 */
class PanelStockerController extends StockerController
{
    /**
     * Remueve un panel del stocker al que se halla asignado
     *
     * @param string $panelBarcode Codigo de panel
     * @return array|null|object
     */
    public function removePanel($panelBarcode)
    {
        $run = new PanelStocker();
        $output = $run->remove($panelBarcode);
        return $output;
    }

    /**
     * Ingresa en TransaccionWip las placas con estado TRANS_OK = 1 con TRANS_DET = "Forced" para permitir al control de placas despachar el stocker
     *
     * @param string $panelBarcode Codigo de panel
     * @return array|object
     */
    public function forceTransaccionWip($panelBarcode)
    {
        $find = new FindInspection();
        $panel = (object) $find->barcode($panelBarcode);

        $output = array();

        if(!isset($panel->error)) {
            $panel = $panel->last->panel;
            $bloques = $panel->joinBloques;

            if(count($bloques)) {
                if($panel->isSecundario()) {
                    $twip = $panel->twip;
                    if (isset($twip)) {
                        $twip->trans_ok = 1;
                        $twip->trans_det = "Forced";
                        $twip->save();

                        $output[] = $twip;
                    } else {
                        $add = new TransaccionWip();
                        $add->barcode = $panel->panel_barcode;
                        $add->trans_ok = 1;
                        $add->trans_id = 12345;
                        $add->trans_det = "Forced";
                        $add->updated_at = Carbon::now();
                        $add->created_at = Carbon::now();
                        $add->id_panel = $panel->id;
                        $add->save();
                        $output[] = $add;
                    }
                } else {
                    foreach ($bloques as $bloque) {
                        $twip = TransaccionWip::where('barcode',$bloque->barcode)->orderBy('id','desc')->first();

                        if ($twip!=null) {
                            if($twip->trans_ok != 1) {
                                $twip->trans_ok = 1;
                                $twip->trans_det = "Forced";
                                $twip->save();

                                $output[] = $twip;

                                dump('Forzando existente',$twip);
                            } else {
                                dump('Ya esta declarado',$twip);
                            }
                        } else {
                            $add = new TransaccionWip();
                            $add->barcode = $bloque->barcode;
                            $add->trans_ok = 1;
                            $add->trans_id = 12345;
                            $add->trans_det = "Forced";
                            $add->updated_at = Carbon::now();
                            $add->created_at = Carbon::now();
                            $add->id_panel = $panel->id;

                            $add->save();

                            dump('Forzando',$add);

                            $output[] = $add;
                        }

                    }
                }
            } else {
                $output = (object) ["error" => "La placa no tiene bloques!, es necesaria una reinspeccion"];
            }
        }
        else
        {
            $output = $panel;
        }

        return $output;
    }

    /**
     * Declara todas las placas de un panel en la Interfaz Wip con su respectivo barcode, siempre y cuando la OP se encuentre abierta
     *
     * @param string $panelBarcode Codigo de placa
     * @return array
     */
    public function declarePanel($panelBarcode)
    {
        $find = new FindInspection();
        $find->onlyLast = true;
        $panel = (object) $find->barcode($panelBarcode);

        $output = array();

        if(!isset($panel->error)) {
            $panel = $panel->last->panel;
            $bloques = $panel->joinBloques;
            $w = new Wip();
            $opinfo = $w->otInfo($panel->inspected_op);

            if(isset($opinfo))
            {
                if(count($bloques)) {
                    foreach ($bloques as $bloque) {
                        $output[] = $w->declarar('UP3', $panel->inspected_op, $opinfo->codigo_producto,1,$bloque->barcode);
                    }
                } else {
                    $output = (object) ["error" => "La placa no tiene bloques!, es necesaria una reinspeccion"];
                }
            } else {
                $output = (object) ["error" => "La OP se encuentra cerrada"];
            }
        } else
        {
            $output = $panel;
        }

        return (array) $output;
    }

    public function declaredDetail($panelBarcode) {
        $find = new FindInspection();
        $find->onlyLast = true;
        $panel = (object) $find->barcode($panelBarcode);

        if(!isset($panel->error)) {
            $panel = $panel->last->panel;

            if ($panel->isSecundario()) {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);

                $panel->verificarDeclaracion = $interfazWip;
            } else {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelEnTransaccionesWipOrCheckInterfazWip($panel);

                $panel->verificarDeclaracion = $interfazWip;
            }

            $output = $panel;

        } else {
            $output = $panel;
        }

        return $output;
    }

    /**
     * Si el codigo de panel es valido, y tiene asignado un stocker, el sistema ingresa el panel al stocker segun el modo de produccion, puede ser con AOI / MANUAL o COGISCAN
     *
     * @param string $panelBarcode Codigo de panel
     * @param string $aoibarcode Codigo de produccion
     * @return array|null|object
     */
    public function addPanel($panelBarcode,$aoibarcode)
    {
        $output = array();
        $stocker = null;

        // Verifica que exista stocker en produccion
        $produccion = Produccion::barcode($aoibarcode);

        if(isset($produccion)) {

            if (isset($produccion->id_stocker))
            {
                switch($produccion->manual_mode)
                {
                    // MODO AOI
                    case 0:
                        $run = new PanelWithAoi();
                        $output = $run->add($panelBarcode,$produccion);
                        break;
                    // MODO MANUAL
                    case 1:
                        $run = new PanelWithAoiDeclare();
                        $output = $run->add($panelBarcode,$produccion);
                        break;
                    // MODO COGISCAN->TH
                    case 3:
                        $run = new PanelWithCogiscan();
                        $output = $run->add('SMT-BPR','BPR',$panelBarcode,$produccion);
                        break;
                }
            } else {
                $error = 'No hay Stocker definido en produccion';
                $output = compact('error','panelBarcode');
            }
        } else {
            $error = 'El codigo de produccion no existe';
            $output = compact('error','panelBarcode');
        }

        return $output;
    }
}
