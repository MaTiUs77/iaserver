<?php
namespace IAServer\Http\Controllers\Aoicollector\Service;

use IAServer\Http\Controllers\Aoicollector\Model\Backup\BackupBloque;
use IAServer\Http\Controllers\Aoicollector\Model\Backup\BackupPanel;
use IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory;
use IAServer\Http\Controllers\Aoicollector\Model\DetalleHistory;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip;
use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\WipSerie;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

set_time_limit(400);

class Service extends Controller
{
    /**
     * El metodo __construct define si se realiza Debug del controlador
     *
     * @var Debug|null
     */
    public $debug = null;

    function __construct()
    {
        $this->debug = new Debug($this,false,'service',false);

        $ip = Request::server('REMOTE_ADDR');
        $host = getHostByAddr(Request::server('REMOTE_ADDR'));
        $message = array(
            "IP" => $ip,
            "Host" => $host,
            "Request Url" => Request::url(),
        );

        $this->debug->put(join(' | ',$message));

        $this->updateTransaccionWipPendientes();
    }

    public function process($lista,$modo='current')
    {
        $lista = str_replace("\r",'',$lista);
        $line = explode("\n",$lista);

        $output = array();

        $output[] = [
            'db',
            'barcode',
            'programa',
            'fecha_maquina',
            'op',
            'modelo',
            'lote',
            'panel',
            'semielaborado',
            'error'
        ];

        foreach($line as $item)
        {
            if(!empty($item))
            {

                if($modo=='old')
                {
                    $result = (object) $this->barcodeInBackup($item);
                    $db = 'DB_2014';
                } else
                {
                    $result = (object) $this->barcodeStatus($item,false,false,false,true);
                    $db = 'current';
                }

                if(isset($result->aoi->panel->fecha))
                {
                    $output[] = [
                        $db
                        ,$item
                        ,$result->aoi->panel->programa
                        ,$result->aoi->panel->fecha
                        ,$result->aoi->panel->inspected_op
                        ,(isset($result->smt->modelo) ? $result->smt->modelo : 'Desconocido')
                        ,(isset($result->smt->lote) ? $result->smt->lote : 'Desconocido')
                        ,(isset($result->smt->panel) ? $result->smt->panel: 'Desconocido')
                        ,(isset($result->smt->semielaborado) ? $result->smt->semielaborado: 'Desconocido')
                        ,''

                    ];

                } else {
                    if (isset($result->error)) {
                        $output[] = [
                            $db
                            , $item
                            , ''
                            , ''
                            , ''
                            , ''
                            , ''
                            , ''
                            , ''
                            , $result->error
                        ];
                    } else {
                        $output[] = [
                            $db
                            , $item
                            , ''
                            , ''
                            , ''
                            , ''
                            , ''
                            , ''
                            , 'Error desconocido'
                        ];
                    }
                }
            }
        }

        Util::convert_to_csv($output,'Export_'.$db.'.csv',',');
    }

    public function barcodeInBackup($barcode)
    {
        $output = null;
        $db = 'old';

        if(is_numeric($barcode))
        {
            $panel = BackupPanel::buscar($barcode);
            if($panel!=null && isset($panel->id))
            {
                $aoi = new \stdClass();
                $aoi->panel = $panel;
                $aoi->bloque = null;

                if (isset($aoi->panel->panel_barcode)) {
                    $bloques = BackupBloque::where('id_panel', $aoi->panel->id)->get();
                    $bloque = array_where($bloques, function ($key, $value) use ($aoi, $barcode) {
                        if ($value->barcode == $barcode) {
                            return $value;
                        }
                    });

                    $aoi->analisis = $this->analisisDespacho($bloques,$aoi->panel);
                    if($aoi->analisis->mode == 'E')
                    {
                        $aoi->bloque = head($bloque);
                    }
                }

                $output = compact('db','aoi');
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

    public function barcodeStatus($barcode,$withWip=false,$withDetail=false, $withProductioninfo=false, $withSmt=false)
    {
        $db = 'current';

        if(is_numeric($barcode)) {
            $panel = PanelHistory::buscar($barcode);

            if($panel!=null)
            {
                $aoi = new \stdClass();
                $aoi->panel = (object)(head(head($panel)));
                $aoi->bloque = null;

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

                    if ($withDetail) {
                        $aoi->detalle = DetalleHistory::fullDetail($aoi->bloque->id_bloque_history)->get();
                    }

                    if ($withProductioninfo) {
                        $aoi->production = Produccion::maquina($aoi->panel->id_maquina);
                    }

                    if ($withSmt) {
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

                    if($withWip)
                    {
                        $wip_serie = $this->barcodeDeclared($barcode, $aoi->panel);
                        $output = compact('db','barcode','aoi','smt','wip_serie');
                    } else
                    {
                        $output = compact('db','barcode','aoi','smt');
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
    public function barcodeDeclared($barcode, PanelHistory $panelHistory=null)
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


    /**
     * Realiza la declaracion de un barcode.
     *
     * Primero verifica si el panel existe en AOI, luego obtiene la OP asignada.
     * Obtiene de WipOt, WipSerie y WipSerieHistory, transacciones y estado de OP
     * Verifica si fue declarado el barcode previamente, de no ser asi, realiza la declaracion
     * Si la OP se encuentra cerrada, o si el barcode ya fue declarado, finaliza la operacion.
     *
     * @param string $barcode
     * @return array
     */
    public function declarar($barcode)
    {
        // Busca el barcode en el historial de aoi
        $panel = PanelHistory::buscar($barcode);
        $panel = head(head($panel));

        $output = array();
        $output['op'] = null;
        $output['estado_op'] = null;
        $output['pendiente'] = false;
        $output['declarado'] = false;
        $output['insert_id'] = null;
        $output['mode'] = null;
        $output['modedeclare'] = null;

        $declarar = false;

        // Si el panel fue guardado con una OP correctamente
        if(isset($panel->inspected_op))
        {
            $output['op'] = $panel->inspected_op;

            // Verifica si la OP se encuentra activa
            $wip = new Wip();
            $wipInfo = $wip->findOp($panel->inspected_op);

            if($wip->active)
            {
                $output['estado_op'] = 'activa';
                // Es posible declarar en wip ???
                $output['mode'] = $wipInfo->transactions->mode;
                $output['modedeclare'] = $wipInfo->transactions->modedeclare;

                if($wipInfo->transactions->modedeclare)
                {
                    // Verifica si el panel fue declarado en Wip
                    $declareInfo = (object) $this->barcodeDeclared($barcode,$panel);
                    if($declareInfo->declarado)
                    {
                        $output['declarado'] = true;
                    } else
                    {
                        if($declareInfo->pendiente) {
                            $output['pendiente'] = true;
                        } else  {
                            $qty = 1;
                            $planta = 'UP3';

                            $insert = $wip->declarar($planta,$wipInfo->wip_ot->nro_op,$wipInfo->wip_ot->codigo_producto,$qty, $barcode);

                            if(isset($insert->id)) {
                                $transaccion = new TransaccionWip();
                                $transaccion->barcode = $barcode;
                                $transaccion->trans_id = $insert->id;
                                $transaccion->trans_code = 0;
                                $transaccion->id_panel= $panel->id;
                                $transaccion->save();

                                $output['insert_id'] = $insert->id;
                            } else {
                                $output['insert_id'] = 0;
                            }
                        }
                    }
                } else {
                    $output['declarado'] = false;
                }

            } else  {
                $output['estado_op'] = 'cerrada';
            }
        }

        $this->debug->put("\n Barcode: $barcode / WipOt: ".$wipInfo->wip_ot->quantity_completed."/".$wipInfo->wip_ot->start_quantity." Trans: ".$wipInfo->transactions->declaradas."/".$wipInfo->transactions->solicitudes." / Pend: ".$wipInfo->transactions->pendientes." \n ". json_encode($output));

        return $output;
    }

    public static function updateTransaccionWipPendientes()
    {
        $pendientes = TransaccionWip::where('trans_code',0)->get();

        foreach($pendientes as $pendiente)
        {
            $serie = new WipSerie();
            $estado = $serie->findByIdTraza($pendiente->trans_id);
            if(isset($estado->trans_ok))
            {
                $pendiente->trans_code = $estado->trans_ok;
                $pendiente->trans_det = $estado->trans_ok_det->description;
                $pendiente->save();
            }
        }
    }
}
