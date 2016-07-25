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
use spec\Prophecy\Promise\RequiredArgumentException;

class ViewPedidos extends Controller
{
    public function getMaterialError(Request $request)
    {
        $pedidos = new CogiscanPedidos();
        $resume = $pedidos->getMaterialError($request->get('partnumber'));

        $output = compact('resume');
        return Response::multiple_output($output, 'monitorpedidos.request_error');

    }

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
        $pedido = $material->showMaterialMysql();

        $reserva = $material->showReserva($request->get('valor'));

        $output = compact('pedido', 'reserva');
        return Response::multiple_output($output, 'monitorpedidos.request_partial_mysql');
    }

    public function getRequestNew()
    {
        $material = new CogiscanPedidos();
        $resume = $material->getRequestNew();

        $output = compact('resume');
        return Response::multiple_output($output, 'monitorpedidos.request_partial');
    }

    public function showReservaXLinea($smt)
    {

        $pedidos = new CogiscanPedidos();
        $reserva = $pedidos->showReservaXLinea($smt);

        $output = compact('reserva');
        return Response::multiple_output($output, 'monitorpedidos.reservas');
    }
}
