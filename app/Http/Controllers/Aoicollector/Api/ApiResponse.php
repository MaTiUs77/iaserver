<?php
namespace IAServer\Http\Controllers\Aoicollector\Api;

use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Requests;
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
        return Response::multiple_output($output);
    }

    public function fullInfoResponse($barcode)
    {
        $output = $this->fullInfo($barcode);
        return Response::multiple_output($output);
    }
}
