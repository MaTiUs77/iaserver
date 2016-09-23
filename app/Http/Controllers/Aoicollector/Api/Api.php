<?php
namespace IAServer\Http\Controllers\Aoicollector\Api;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
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

set_time_limit(400);

class Api extends Controller
{
    public $debug = null;

    function __construct()
    {
        $this->debug = new Debug($this,false,'api',false);

        $ip = Request::server('REMOTE_ADDR');
        $host = getHostByAddr(Request::server('REMOTE_ADDR'));
        $message = array(
            "IP" => $ip,
            "Host" => $host,
            "Request Url" => Request::url(),
        );

        $this->debug->put(join(' | ',$message));
    }

    public function verifyPlaca($barcode,$stage="")
    {
        $findPanel = new FindInspection();
        $findPanel->withSmt = true;
        $findPanel->onlyLast = true;
//        $findPanel->withWip = true;

        $panel = $findPanel->barcode($barcode);

        $output = new \stdClass();

        if(isset($panel->last))
        {
            $data = $panel->last;
            $panel = $data->panel;

            $output->barcode = $barcode;
            $output->op = $panel->inspected_op;
            $output->smt = $data->smt;

            // Verifico si el panel es secundario
            if($panel->isSecundario())
            {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);
                $output->declaracion = $interfazWip->declaracion;
            } else
            {
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
        } else
        {
            $output = $panel;
        }

        return Response::multiple_output($output);
    }
}
