<?php
namespace IAServer\Http\Controllers\Aoicollector\Api\CollectorClient;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Redis\RedisBroadcast;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

/**
 * Webservice utilizado por el cliente AOICollector para verificar estado de placas
 *
 * @package IAServer\Http\Controllers\Aoicollector\Api\CollectorClient
 */
class CollectorClientApi extends Controller
{
    /**
    * Obtiene el ultimo estado de una placa y verifica si fue declarado
     *
    * Webservice {@url http://arushap34/iaserver/public/api/verify/placa/0010854977/PuestoSatelite1}
    *
    * @param string $barcode Codigo de placa
    * @param string $stage Nombre de puesto que consulta el metodo, actualmente no loguea, pero esta pensado para tal caso
    *
    * @example code
    * <php>
    *      $client = new CollectorClientApi();
    *      $client->verifyBarcode('0010854977','Puesto1');
    * </php>
    * @return array
    * {"barcode":"0010854977","op":"OP-122699","smt":{"id":8346,"op":"OP-122699","modelo":"DC32X4000X","lote":"L101","panel":"POW","prod_aoi":6452,"prod_man":0,"qty":6452,"semielaborado":"4-651-IAPOWTV00218"},"revision":"OK","declaracion":{"declarado":true,"pendiente":false,"error":false}}
    */

    public function verifyBarcode($barcode, $stage)
    {
        $output = new \stdClass();

        if(ctype_alnum($stage)){

            $findPanel = new FindInspection();
            $findPanel->withSmt = true;
            $findPanel->onlyLast = true;

            $panel = $findPanel->barcode($barcode);

            if(isset($panel->last))
            {
                $data = $panel->last;
                $panel = $data->panel;

                $output->barcode = $barcode;
                $output->op = $panel->inspected_op;
                $output->smt = $data->smt;

                if(isset($data->bloque)) {
                    $output->revision = $data->bloque->revision_ins;

                    // Verifico si el panel es secundario
                    if ($panel->isSecundario()) {
                        $verify = new VerificarDeclaracion();
                        $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);
                        $output->declaracion = $interfazWip->declaracion;
                    } else {
                        $verify = new VerificarDeclaracion();
                        $interfazWip = $verify->panelEnTransaccionesWipOrCheckInterfazWip($panel);
                        $output->declaracion = $interfazWip->declaracion;
                    }
                    // Esconder algunos datos
                    unset($output->declaracion->parcial);
                    unset($output->declaracion->declarado_total);
                    unset($output->declaracion->parcial_total);
                    unset($output->declaracion->pendiente_total);
                    unset($output->declaracion->error_total);
                } else {
                    $output->error = 'No se localizo el barcode en AOI';
                }
            } else
            {
                $output = $panel;
            }
        }
        else{
            $output->error = 'El nombre de referencia no es alphanumerico';
        }

        return (array) $output;
    }

    /**
     * Busca el ultimo estado de una placa, si existe define la placa en la ruta de P3 - Piso de planta
     *
     * Webservice {@url http://arushap34/iaserver/public/api/aoicollector/placa/0011009543}
     *
     * @param string $barcode Codigo de placa
     * @param bool $verifyDeclared Verifica si la placa fue declarada
     * @return array
     * {"barcode":"0011009543","panel":{"id_panel_history":3184651,"modo":"update","id":5289744,"id_maquina":10,"panel_barcode":"0011009543","programa":"1553856-LR","fecha":"2017-02-21","hora":"11:35:19","turno":"M","revision_aoi":"NG","revision_ins":"OK","errores":4,"falsos":4,"reales":0,"bloques":2,"etiqueta":"E","pendiente_inspeccion":0,"test_machine_id":0,"program_name_id":0,"inspected_op":"OP-123006","semielaborado":null,"id_user":null,"created_date":"2017-02-21","created_time":"11:35:28"}}
     */
    public function findBarcode($barcode,$verifyDeclared=false)
    {
        $output = new \stdClass();

        $findPanel = new FindInspection();
        $findPanel->withSmt = true;
        $findPanel->onlyLast = true;
        //$findPanel->withWip = true;

        $result = $findPanel->barcode($barcode);

        if(isset($result->last))
        {
            if(isset($result->last->wip))
            {
                $wip = $result->last->wip;
                if(isset($wip->twip)) {
                    $twip = $wip->twip;
                    if($twip->id_last_route!=1) {
                        $twip->id_last_route = 1; // P3 Piso de planta
                        $twip->save();
                    }
                } else {
                   // dd('Sin twip');
                }
            }

            $panel = $result->last->panel;

            $output->barcode = $barcode;
            $output->panel = $panel;
           // $output->wip = $wip;

            /*// Verifico si el panel es secundario
            if($verifyDeclared) {
                if ($panel->isSecundario()) {
                    $verify = new VerificarDeclaracion();
                    $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);
                    $output->interfaz = $interfazWip->declaracion;
                } else {
                    $verify = new VerificarDeclaracion();
                    $interfazWip = $verify->panelEnTransaccionesWipOrCheckInterfazWip($panel);
                    $output->interfaz = $interfazWip->declaracion;
                }
            }*/
        } else
        {
            $output = $result;
        }

        return (array) $output;
    }

    /**
     * Muestra el estado de produccion de un barcode solicitado
     *
     * <p>
     * Adjunta informacion de: Smt, Transaction, Stocker, y Control de placas.
     *
     * Guarda en un KEY en Redis llamado <b>prodinfo:$elbarcode</b>, permanece por 60 segundos,
     * a su vez publica en el CHANNEL denominado de la misma manera.
     * </p>
     *
     * <p>
     * Webservice {@url http://arushap34/iaserver/public/api/aoicollector/prodinfo/VTRNS6143}
     * </p>
     *
     * @param string $aoibarcode Codigo de produccion
     * @return array
     * {"barcode":"0011009543","panel":{"id_panel_history":3184651,"modo":"update","id":5289744,"id_maquina":10,"panel_barcode":"0011009543","programa":"1553856-LR","fecha":"2017-02-21","hora":"11:35:19","turno":"M","revision_aoi":"NG","revision_ins":"OK","errores":4,"falsos":4,"reales":0,"bloques":2,"etiqueta":"E","pendiente_inspeccion":0,"test_machine_id":0,"program_name_id":0,"inspected_op":"OP-123006","semielaborado":null,"id_user":null,"created_date":"2017-02-21","created_time":"11:35:28"}}
     */
    public function prodInfo($aoibarcode)
    {
        $aoibarcode = strtoupper($aoibarcode);

        $output = Produccion::fullInfo($aoibarcode,[
            'smt'=>true,
            'transaction'=>true,
            'stocker'=>true,
            'placas'=>true,
            'period' => false,
            'sfcsroute' => false
        ]);

        $prodInfo = (object) $output;

        // Hace un SET KEY con el nombre del canal, y luego emite un public en el canal
        $redisProd = new RedisBroadcast("prodinfo:$aoibarcode");
        $redisProd->emit($output);

        /*
        if(isset($prodInfo->produccion->stocker->barcode))
        {
            // Guarda los datos del stocker, se mantienen por 1 semana
            $now = Carbon::now();
            $new = clone $now;
            $expire = $new->addWeek(1);

            $redisStocker = new RedisBroadcast("stocker:".$prodInfo->produccion->stocker->barcode.":info");
            $redisStocker->emit($prodInfo->produccion->stocker,$now->diffInSeconds($expire));
        }
        */


        return $output;
    }

    /**
     * Lista de produccion
     *
     * Webservice {@url http://arushap34/iaserver/public/api/aoicollector/prodlist}
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * [{"id":1,"barcode":"IMSMD9","linea":"SMD-9","op":null,"line_id":null,"puesto_id":null,"id_maquina":null,"modelo_id":null,"id_stocker":null,"inf":"BPR","manual_mode":"1","id_route_op":null,"id_user":null,"cogiscan":"N"},{"id":3,"barcode":"VTRNS6143","linea":null,"op":"OP-125196","line_id":2966,"puesto_id":6900,"id_maquina":1,"modelo_id":"2975","id_stocker":73,"inf":null,"manual_mode":"0","id_route_op":null,"id_user":null,"cogiscan":"T"}]
     */
    public function prodList()
    {
        return Produccion::all();
    }
}
