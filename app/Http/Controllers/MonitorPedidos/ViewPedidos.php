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
    public function requestXLinea($prod_line)
    {


        $pedidos = new CogiscanPedidos();
        $resume = $pedidos->getRequestXLinea($prod_line);

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
    public function traza_pedido($insert_id)
    {

        $pedidos = new CogiscanPedidos();
        $traza = $pedidos->getTrazaPedidos($insert_id);
        $traza_complete = $pedidos->trazabilidad($insert_id);
        $output = compact('traza','traza_complete');
        return Response::multiple_output($output,'monitorpedidos.trazaPedido');
    }
    public function verHistorialPartNumber(Request $partnumber)
    {
                $pedido = new CogiscanPedidos();

                    $historial_cgs = $pedido->getRequestPartNumber($partnumber->get('partnumber'), $partnumber->get('prod_line'));
                    $historial_deltamonitor = $pedido->getRequestDeltaMonitor($partnumber->get('partnumber'));
                    $historial_reservas = $pedido->getDeliveryPartNumber($partnumber->get('partnumber'));
                    $historial_interfaz = $pedido->getRequestPartial($partnumber->get('partnumber'));
                    $historial_interfaz_error = $pedido->getMaterialError($partnumber->get('partnumber'));
                    $historial_idXlpn = $pedido->getIdRequestXLpn($partnumber->get('partnumber'));
                    if ($historial_idXlpn != null) {
                        $historial_pedidoXlpn = $pedido->getRequestXLpn($historial_idXlpn->id);
                        $historial_reservaXid = $pedido->getRequestXidReservas($historial_idXlpn->id);
                        $output = compact('historial_cgs', 'historial_deltamonitor', 'historial_reservas', 'historial_interfaz', 'historial_interfaz_error', 'historial_pedidoXlpn', 'historial_reservaXid');
                        return Response::multiple_output($output, 'monitorpedidos\consultas.consultas');
                    } else {
                        $historial_pedidoXlpn = collect([]);
                        $historial_reservaXid = collect([]);
                        $output = compact('historial_cgs', 'historial_deltamonitor', 'historial_reservas', 'historial_interfaz', 'historial_interfaz_error', 'historial_pedidoXlpn', 'historial_reservaXid');
                        return Response::multiple_output($output, 'monitorpedidos\consultas.consultas');
                    }
    }
    public function reservaTransito()
    {

            $pedidos = new CogiscanPedidos();
            $reserva = $pedidos->getRequestTransito();

            $output = compact('reserva');
            return Response::multiple_output($output, 'monitorpedidos.transitview');

    }
    public function reservaTransitoXlinea($linea)
    {

        $pedidos = new CogiscanPedidos();
        $reserva = $pedidos->getRequestTransitoReserva($linea);

        $output = compact('reserva');
        return Response::multiple_output($output, 'monitorpedidos.transitview');
    }

    public function traza_complete($id,$item_code)
    {
            $partnumber = new CogiscanPedidos();
            $trazaPartNumber = $partnumber->trazabilidad($id,$item_code);
            dd($trazaPartNumber);
            $output = compact('trazaPartNumber');
            return Response::multiple_output($output, 'monitorpedidos.traza_complete');
   }
}
