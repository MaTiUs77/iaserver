<?php
namespace IAServer\Http\Controllers\Aoicollector\Api;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Stat\StatExport;
use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Controllers\Redis\RedisController;
use IAServer\Http\Requests;
use IAServer\Jobs\StartExportJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

set_time_limit(400);

class ApiResponse extends Api
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

    public function verifyPlacaResponse($barcode,$stage)
    {
        $output = $this->verifyPlaca($barcode,$stage);
        return Response::multiple($output);
    }

    public function p5Response($barcode,$stage)
    {

        $placa = (object) $this->verifyPlaca($barcode,$stage);

//        $redis = new RedisController();
//        $redis->push($barcode.$stage,json_encode($placa));
//        $cache = $redis->cached($barcode.$stage);
//        $placa = $cache;

        if(isset($placa->error))
        {
            $output = ['error'=>$placa->error];
        } else {
            $output = [
                'barcode' => $placa->barcode,
                'op' => $placa->smt->op,
                'modelo' => $placa->smt->modelo,
                'lote' => $placa->smt->lote,
                'panel' => $placa->smt->panel,
                'semielaborado' => $placa->smt->semielaborado,
                'controldeplacas' => $placa->declaracion->declarado
            ];
        }

        return Response::multiple($output);
    }

    public function aoicollectorPlacaResponse($barcode,$verifyDeclared="")
    {
        $output = $this->aoicollectorPlaca($barcode,$verifyDeclared);
        return Response::multiple($output);
    }

    public function aoicollectorProdInfoResponse($aoibarcode)
    {
        $output = $this->aoicollectorProdInfo($aoibarcode);
        return Response::multiple($output);
    }

    public function prodListResponse()
    {
        $output = Produccion::all();
        return Response::multiple($output);
    }
}
