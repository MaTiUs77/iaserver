<?php

namespace IAServer\Http\Controllers\MonitorPedidos;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\MonitorPedidos\CogiscanPedidos;
use IAServer\Http\Controllers\P2i\Model\Carga;

use IAServer\Http\Controllers\MonitorPedidos\Model\XXE_WMS_COGISCAN_PEDIDOS;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ViewPedidos extends Controller
{
    public function index(Request $request)
    {


        $pedidos = new CogiscanPedidos();
        $resume = $pedidos->getRequestPartial($request->get('partnumber'));

        $output = compact('resume');
        return Response::multiple_output($output, 'monitorpedidos.request_partial');
    }

    public function viewMysql(Request $request)
    {
        $material = new CogiscanPedidos();
        $pedido = $material->showMaterialMysql($request->get('partnumber'));

        $output = compact('pedido');
        return Response::multiple_output($output, 'monitorpedidos.request_partial_mysql');
    }


}
