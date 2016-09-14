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
                $interfazWip = $verify->panelEnInterfazWip($panel);

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

    public function stockerDetail(Stocker $stocker)
    {
        /*$detalle = [];

        foreach($content as $stkdet)
        {
            $obj = new \stdClass();
            $obj->panel_declarado = false;
            $obj->panel_pendiente = false;
            $obj->panel_errores = false;
            $obj->panel_count = 0;

            $panel  = $stkdet->joinPanel;
            $bloquesArray = $panel->joinBloques;

            //$obj->panel = $panel;
            $obj->panel_declarado_total = 0;
            $obj->bloques = [];

            // Si el numero de bloques, coincide con el numero de etiquetas virtuales
            if($panel->isSecundario())
            {
                // Verifico en wip todos los codigos registrados con este (barcode-xx)
                $bwip = $panel->wipSecundario();
                $obj->panel_declarado_total = $bwip->historial->where('trans_ok','1')->count();
                if($panel->bloques == $obj->panel_declarado_total)
                {
                    $obj->panel_declarado = true;
                }

                if($bwip->historial->where('trans_ok','0')->count()>0)
                {
                    $obj->panel_pendiente = true;
                }

                $obj->bloques = $bwip->historial;
            } else
            {
                foreach($bloquesArray as $block)
                {
                    $verify = new VerificarDeclaracion();
                    $twip = $verify->bloqueEnTransaccionWip($block->barcode);

                    if($twip != null && $twip->declarado)
                    {
                        $obj->bloques[] = $twip;
                    } else
                    {
                        $bwip = $verify->bloqueDePanel($block->barcode,$stocker->op);
                        if($bwip->last!=null)
                        {
                            if(isset($bwip->last->id))
                            {
                                $twip = new TransaccionWip();
                                $twip->barcode = $block->barcode;
                                $twip->trans_id = $bwip->last->id;
                                $twip->trans_ok = $bwip->last->trans_ok;
                                $twip->trans_det = null;
                                $twip->id_panel = $block->id;
                                $twip->save();
                            }

                            $bwip->last->declarado = $bwip->declarado;
                            $bwip->last->pendiente = $bwip->pendiente;

                            if($bwip->last->trans_ok > 1 )
                            {
                                $obj->panel_errores = true;
                            }
                        }
                        $obj->bloques[] = $bwip->last;
                    }
                }

                $obj->panel_declarado_total = collect($obj->bloques)->where('declarado',true)->count() ;
                if(count($obj->bloques) == $obj->panel_declarado_total)
                {
                    $obj->panel_declarado = true;
                }
                if(count($obj->bloques) == collect($obj->bloques)->where('pendiente',true)->count())
                {
                    $obj->panel_pendiente = true;
                }
            }

            $detalle[] = $obj;
        }

        return $detalle;*/
    }
}
