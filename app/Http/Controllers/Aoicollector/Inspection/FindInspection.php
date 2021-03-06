<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use IAServer\Http\Controllers\Aoicollector\Cuarentena\CuarentenaController;
use IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class FindInspection extends Controller
{
    /**
     * @var bool Verifica si la placa se encuentra en TransaccionesWip y adjunta el resultado
     */
    public $withWip = false;
    /**
     * @var bool
     */
    public $withDetail = false;
    /**
     * @var bool
     */
    public $withProductioninfo = false;
    /**
     * @var bool Adjunta informacion de SMT relacionada a la OP de la inspeccion
     */
    public $withSmt = false;
    /**
     * @var bool Adjunta trazabilidad de Cogiscan
     */
    public $withCogiscan = false;
    /**
     * @var bool Devuelve solo la ultima inspeccion de la placa
     */
    public $onlyLast = false;
    /**
     * @var bool Adjunta el historial de inspecciones de la placa
     */
    public $withHistory = false;
    /**
     * @var bool Adjunta ultima ruta de la placa
     */
    public $withLastRoute = false;
    /**
     * @var bool Adjunta estado de cuarentena
     */
    public $withCuarentena = false;

    public function barcode($barcode)
    {
        $barcode = trim($barcode);
        $db = 'current';

        // El barcode es valido??
        if(is_numeric($barcode)) {
            // Buscar en BloqueHistory, retorna los resultados en orden descendiente
            // la primer inspeccion seria la ultima en el array de resultados

            $placas = BloqueHistory::buscar($barcode);

            if(count($placas)>0)  {
                if(count($placas)==1)  {

                    $result = new \stdClass();
                    $result->first = $this->panelDataHandler($placas->last());
                    $result->last = $result->first;

                    if($this->withHistory ) {
                        $result->historial[] = $result->first;
                    }
                } else  {
                    $result = new \stdClass();

                    if($this->onlyLast) {
                        $result->first = null;
                    } else {
                        $result->first = $this->panelDataHandler($placas->last());
                    }

                    $result->last = $this->panelDataHandler($placas->first());

                    if($this->withHistory ) {
                        $result->historial = null;

                        foreach ($placas as $history) {
                            $result->historial[] = $this->panelDataHandler($history);
                        }
                    }
                }

                return $result;
            } else
            {
                /*
                 * Sera una placa secundaria? en bloques las placas secundarias tienen el formato
                 * 0000123-XX y nosotros estamos buscando 0000123
                 * Hay que buscar en paneles el codigo sin el guion
                 */
                $panel = PanelHistory::buscarPanel($barcode);

                if(count($panel)>0) {
                    $result = new \stdClass();

                    if($this->onlyLast) {
                        $result->first = null;
                    } else {
                        $result->first = $this->panelDataHandler($panel->last());
                    }

                    $result->last = $this->panelDataHandler($panel->first());

                    if ($this->withHistory) {
                        $result->historial = null;

                        foreach ($panel as $history) {
                            $result->historial[] = $this->panelDataHandler($history);
                        }
                    }

                    return $result;
                } else
                {
                    $error = "No se localizo el barcode en AOI";
                    $output = compact('db','barcode', 'error');
                }
            }
        } else
        {
            $error = "El dato es invalido, solo se permite barcode numerico.";
            $output = compact('db','barcode', 'error');
        }

        return $output;
    }

    private function panelDataHandler($placa)
    {
        $moreInfo = new \stdClass();

        if($placa instanceof PanelHistory)
        {
            $moreInfo->panel = $placa;
            $moreInfo->bloque = null;
        }

        if($placa instanceof BloqueHistory)
        {
            $moreInfo->panel = $placa->panel;
            $moreInfo->bloque = $placa;
        }

        $bloques = BloqueHistory::where('id_panel_history', $moreInfo->panel->id_panel_history)->get();
        $moreInfo->analisis = $this->analisisDespacho($bloques,$moreInfo->panel);

        if($this->withSmt) {
            $moreInfo->smt = $moreInfo->panel->smt();
        }

        if($this->withCogiscan)
        {
            $moreInfo->cogiscan = $moreInfo->panel->cogiscan();
        }

        if($this->withWip)
        {
            $verify = new VerificarDeclaracion();
            $verifyResult = $verify->bloqueEnTransaccionWip($placa->barcode);
            $moreInfo->wip = $verifyResult;
        }

        if($this->withCuarentena)
        {
            $cuarentena = new CuarentenaController();
            $moreInfo->cuarentena = $cuarentena->getDetail($placa->barcode);
        }

        return $moreInfo;
    }
/*
 * BACKUP DE FUNCION
    public function barcode($barcode)
    {
        $db = 'current';

        // Si el barcode es valido, se realiza la busqueda en PanelHistory
        if(is_numeric($barcode)) {
            $panel = PanelHistory::buscar($barcode);

            // Si encontro resultados...
            if($panel!=null)
            {
                $result = new \stdClass();
                $result->last = $this->panelDataHandler($barcode,collect($panel)->first());

                if(count($panel)>1 && !$this->onlyLast )
                {
                    $result->historial = null;

                    foreach($panel as $historyPanel)
                    {
                        $result->historial[] = $this->panelDataHandler($barcode,$historyPanel);
                    }
                }

                return $result;
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
    }*/

/*
    private function panelDataHandler($barcode,  $panel,$debug=false)
    {
        $moreInfo = new \stdClass();
        $moreInfo->panel = $panel;
        $moreInfo->bloque = null;
        $moreInfo->detalle = null;
        $moreInfo->production = null;
        $moreInfo->smt = null;
        $moreInfo->analisis = null;
        $moreInfo->wip = null;

        if (isset($panel->panel_barcode))
        {
            $bloques = BloqueHistory::where('id_panel_history', $panel->id_panel_history)->get();

            $bloque = $bloques->where('barcode',$barcode)->first();

            $moreInfo->analisis = $this->analisisDespacho($bloques,$panel);
            if($moreInfo->analisis->mode == 'E')
            {
                $moreInfo->bloque = $bloque;
            }

            if ($this->withDetail) {
                if($bloque!=null)
                {
                    $moreInfo->detalle = DetalleHistory::fullDetail($bloque->id_bloque_history)->get();
                }
            }

            if ($this->withProductioninfo) {
                $moreInfo->production = Produccion::maquina($panel->id_maquina);
            }

            if($this->withSmt) {
                $moreInfo->smt = $panel->smt();
            }
            if($this->withCogiscan)
            {
                $moreInfo->cogiscan = $panel->cogiscan();
            }

            if($this->withWip)
            {
                $verify = new VerificarDeclaracion();
                $verifyResult = $verify->bloqueEnTransaccionWip($bloque->barcode);
                $moreInfo->wip = $verifyResult;
            }
            return $moreInfo;

        } else {
            $error = "No se localizo el barcode en AOI";
            return compact('error');
        }
    }
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
}