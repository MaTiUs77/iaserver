<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory;
use IAServer\Http\Controllers\Aoicollector\Model\DetalleHistory;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class FindInspection extends Controller
{
    public $withWip = false;
    public $withDetail = false;
    public $withProductioninfo = false;
    public $withSmt = false;
    public $withCogiscan = false;

    public function barcode($barcode)
    {
        $db = 'current';

        // Si el barcode es valido, se realiza la busqueda en PanelHistory
        if(is_numeric($barcode)) {
            $panel = PanelHistory::buscar($barcode);

            // Si encontro resultados...
            if($panel!=null)
            {
                $aoi = new \stdClass();
                $aoi->panel = (object)(head(head($panel)));
                $aoi->bloque = null;
                $aoi->historial = $panel;

                $wip = null;
                $declarado = false;
                $error = null;

                if (isset($aoi->panel->panel_barcode))
                {
                    $bloques = BloqueHistory::where('id_panel_history', $aoi->panel->id_panel_history)->get();
                    $bloque = array_where($bloques, function ($key, $value) use($aoi, $barcode) {
                        if($value->barcode == $barcode){
                            return $value;
                        }
                    });
                    $aoi->analisis = $this->analisisDespacho($bloques,$aoi->panel);
                    if($aoi->analisis->mode == 'E')
                    {
                        $aoi->bloque = head($bloque);
                    }

                    if ($this->withDetail) {
                        $aoi->detalle = DetalleHistory::fullDetail($aoi->bloque->id_bloque_history)->get();
                    }

                    if ($this->withProductioninfo) {
                        $aoi->production = Produccion::maquina($aoi->panel->id_maquina);
                    }

                    if ($this->withSmt) {
                        $w = new Wip();
                        $smt = SMTDatabase::findOp($aoi->panel->inspected_op);
                        $wipResult = $w->findOp($aoi->panel->inspected_op,false,false);

                        $semielaborado =null;
                        if(isset($wipResult->wip_ot->codigo_producto))
                        {
                            $semielaborado = $wipResult->wip_ot->codigo_producto;
                        }
                        $smt->semielaborado = $semielaborado;

                        unset($smt->op);
                        unset($smt->id);
                        unset($smt->prod_aoi);
                        unset($smt->prod_man);
                        unset($smt->qty);
                    }

                    if ($this->withCogiscan)
                    {
                        $cogiscanService= new Cogiscan();
                        $cogiscan = $cogiscanService->queryItem($aoi->panel->panel_barcode);
                    }

                    if($this->withWip)
                    {
                        $wip_serie = $this->barcodeDeclared($barcode, $aoi->panel);
                        $output = compact('db','barcode','aoi','smt','cogiscan','wip_serie');
                    } else
                    {
                        $output = compact('db','barcode','aoi','smt','cogiscan');
                    }

                } else {
                    $error = "No se localizo el barcode en AOI";
                    $output = compact('db','barcode', 'error');
                }
            } else
            {
                $error = "No se localizo el barcode en AOI";
                $output = compact('db','barcode', 'error');
            }

        } else
        {
            $error = "El dato es invalido, solo se permite barcode numerico.";
            $output = compact('db','barcode', 'error');
        }

        return $output;
    }

    /**
     * Verifica el modo del panel, si es virtual o no, y si es posible el despacho
     *
     * @param BloqueHistory $bloqueHistory
     * @param PanelHistory $panelHistory
     * @return \stdClass
     */
    private function analisisDespacho($bloqueHistory, $panelHistory)
    {
        $info = new \stdClass();
        $info->despachar = false;
        $info->mode = 'U';

        $info->etiqueta_fisica = count(array_where($bloqueHistory, function ($key, $value) {
            if($value->etiqueta=='E'){
                return $value;
            }
        }));

        $info->etiqueta_virtual = count(array_where($bloqueHistory, function ($key, $value) {
            if($value->etiqueta=='V'){
                return $value;
            }
        }));

        if($info->etiqueta_fisica == $panelHistory->bloques) {
            $info->despachar = true;
            $info->mode = 'E';
        }

        if($info->etiqueta_virtual == $panelHistory->bloques) {
            $info->despachar = true;
            $info->mode = 'V';
        }

        if($panelHistory->revision_ins == 'OK' && $info->mode != 'U')
        {
            $info->despachar = true;
        }

        return $info;
    }

    /**
     * Verifica si el codigo de barra fue declarado en WipSerie o si se encuentra en WipSerieHistory
     *
     * @param string $barcode
     * @param PanelHistory|null $panelHistory null
     * @return array
     */
    private function barcodeDeclared($barcode, PanelHistory $panelHistory=null)
    {
        $panel = null;

        if($panelHistory != null)
        {
            $panel = $panelHistory;
        } else
        {
            $panelHistory = PanelHistory::buscar($barcode);
            $panel = (object)(head(head($panelHistory)));
        }

        $w = new Wip();
        $wip = $w->findBarcode($barcode, $panel->inspected_op);

        $declarado = false;
        $pendiente = false;

        if(count($wip)>0)
        {
            $findTransOk1= array_first($wip, function ($index,$obj) {
                if($obj->trans_ok == 1)
                {
                    return $obj;
                }
            });

            if(count($findTransOk1)==1)
            {
                $declarado = true;
            }

            $findTransOk0= array_first($wip, function ($index,$obj) {
                if($obj->trans_ok == 0)
                {
                    return $obj;
                }
            });

            if(count($findTransOk0)==1)
            {
                $pendiente = true;
            }
        }

        $output = array();
        $output['declarado'] = $declarado;
        $output['pendiente'] = $pendiente;
        $output['wip'] = $wip;

        return $output;
    }
}