<?php

namespace IAServer\Http\Controllers\MonitorPedidos;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\MonitorOp\GetWipOtInfo;
use IAServer\Http\Controllers\MonitorPedidos\Model\cgs_materialrequest;
use IAServer\Http\Controllers\MonitorPedidos\Model\ins_result;
use IAServer\Http\Controllers\MonitorPedidos\Model\reserva_history;
use IAServer\Http\Controllers\MonitorPedidos\Model\reservas;
use IAServer\Http\Controllers\P2i\Model\Carga;
use Illuminate\Http\Request;
use IAServer\Http\Controllers\P2i\Model\General;
use IAServer\Http\Controllers\MonitorPedidos\Model\XXE_WMS_COGISCAN_PEDIDOS;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CogiscanPedidos extends Controller
{

    public function getMaterialError($item_code)
    {
        $carbon = new Carbon();
        $carbon = Carbon::today();
        if(trim($item_code) != NULL){

            return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
                ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
                ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
                ->WHERE('PEDIDOS.STATUS','ERROR')
                ->WHERE('PEDIDOS.ITEM_CODE',$item_code)
                ->WHERE('PEDIDOS.LAST_UPDATE_DATE','>=',$carbon)
                ->ORDERBY('PEDIDOS.LINEA_ID','DESC')
                ->paginate(60);
        }
        else {
            return XXE_WMS_COGISCAN_PEDIDOS::WHERE('STATUS','<>', 'NEW')
                ->WHERE('STATUS','<>','PROCESSED')
                ->WHERE('LAST_UPDATE_DATE','>=',$carbon)
                ->orderby ('LINEA_ID','DESC')
                ->paginate(60);
        }
    }
    public function getRequestPartial($item_code)
    {
        $carbon = new Carbon();
        $carbon = Carbon::today();
            if(trim($item_code) != NULL){

                return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
                    ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
                    ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
                    ->WHERE('PEDIDOS.STATUS','PROCESSED')
                    ->WHERE('PEDIDOS.ITEM_CODE',$item_code)
                    ->WHERE('PEDIDOS.LAST_UPDATE_DATE','>=',$carbon)
                    ->ORDERBY('PEDIDOS.LINEA_ID','DESC')
                    ->paginate(60);
            }
            else {
                return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
                    ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
                    ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
                    ->WHERE('PEDIDOS.STATUS','PROCESSED')
                    ->WHERE('PEDIDOS.LAST_UPDATE_DATE','>=',$carbon)
                    ->ORDERBY('PEDIDOS.LINEA_ID','DESC')
                    ->paginate(60);
            }
        }
    public function getRequestNew()
    {
        return XXE_WMS_COGISCAN_PEDIDOS::WHERE('STATUS','NEW')
            ->ORDERBY('LAST_UPDATE_DATE','DESC')
            ->paginate(60);
    }
    public function store(Request $request)
    {
        $fecha = new Carbon();
        $fecha = Carbon::now();

        $newInsert = new cgs_materialrequest();
        $newInsert->op = strtoupper($request->input('op_number'));
        $newInsert->linMatWip = "1";
        $newInsert->rawMaterial = "M".$fecha;
        $newInsert->codMat = strtoupper($request->input('item_code'));
        $newInsert->uniMedMat = "EA";
        $newInsert->cantASolic = strtoupper($request->input('quantity'));
        $newInsert->cantTareas = "0";
        $newInsert->cantTransfer = "0";
        $newInsert->estadoLinea = "0";
        $newInsert->linDest = strtoupper($request->input('prod_line'));
        $newInsert->ubicacionOrigen = "5";
        $newInsert->status = "127";
        $newInsert->delta = "0";
        $newInsert->insertId = "0";

        $newInsert->save();

        $newid = $newInsert->id;


        $newrequest = new XXE_WMS_COGISCAN_PEDIDOS();

        $newrequest->OP_NUMBER = strtoupper($request->input('op_number'));
        $newrequest->ORGANIZATION_CODE = "UP3";
        $newrequest->OPERATION_SEQ = "1";
        $newrequest->ITEM_CODE = strtoupper($request->input('item_code'));
        $newrequest->ITEM_UOM_CODE = "EA";
        $newrequest->QUANTITY = strtoupper($request->input('quantity'));
        $newrequest->PROD_LINE = strtoupper($request->input('prod_line'));
        $newrequest->MAQUINA = strtoupper($request->input('maquina'));
        $newrequest->UBICACION = strtoupper($request->input('ubicacion'));
        $newrequest->STATUS = "NEW";
        $newrequest->INSERT_ID = $newid;

        $newrequest->save();

        return redirect('amr/parciales');
    }
    public function update(Request $request, $item_code)
    {

        $pedirdenuevo = XXE_WMS_COGISCAN_PEDIDOS::find($item_code);

        $pedirdenuevo->OP_NUMBER = $request->input('op_number');
        $pedirdenuevo->ITEM_CODE = $request->input('item_code');
        $pedirdenuevo->QUANTITY = $request->input('quantity');
        $pedirdenuevo->PROD_LINE = $request->input('prod_line');
        $pedirdenuevo->MAQUINA = $request->input('maquina');
        $pedirdenuevo->UBICACION = $request->input('ubicacion');
        $pedirdenuevo->save();

        return 'exitosos'.$pedirdenuevo->id;
    }
    public function showReserva($item_code)
    {
//        dd($item_code,$linea);
        $carbon = new Carbon();
        $carbon = Carbon::today();

        if(trim($item_code) != NULL)
        {
            return reservas::where('pn',$item_code)
                ->where('status','=',0)
                ->where('tiempopedido','>',$carbon)
                ->orderby('tiempopedido','desc')
                ->distinct('id_pedido')
                ->get();
        }else
        {
            return reservas::where('timestamp', '>', $carbon)
                ->where('status','=',0)
                ->orderby('tiempopedido', 'desc')
                ->distinct('id_pedido')
                ->get();

        }

    }
    public function showReservaXLinea($smt)
    {

        $carbon = new Carbon();
        $carbon = Carbon::today();

        if(trim($smt) != NULL)
        {
            return reservas::where('linea',$smt)
                ->where('status','=',0)
                ->where('tiempopedido','>',$carbon)
                ->orderby('tiempopedido','desc')
                ->distinct('id_pedido')
                ->get();
        }else
        {
            return reservas::where('timestamp', '>', $carbon)
                ->where('status','=',0)
                ->orderby('tiempopedido', 'desc')
                ->distinct('id_pedido')
                ->get();

        }
    }
    public function showMaterialMysql()
    {

        $carbon = new Carbon();
        $carbon = Carbon::today();
//        dd($carbon);
//        if(trim($partnumber) != NULL)
//        {
//
//            return $estadomaterial = cgs_materialrequest::select(DB::raw("cgs_materialrequest.*, cgs_status.estadoUbicacion"))
//                ->FROM('cgs_materialrequest')
//                ->LEFTJOIN("cgs_status", "cgs_status.idStatus", '=', 'cgs_materialrequest.ubicacionOrigen')
//                ->where('cgs_status.estadoUbicacion', '=', 'almacen')
//                ->where('cgs_materialrequest.codMat',$partnumber)
//                ->where('cgs_materialrequest.timestamp','>',$carbon)
//                ->orderby('timestamp', 'desc')
//                ->paginate(60);
//
//        }
//        else
//        {
            return $estadomaterial = cgs_materialrequest::where('ubicacionOrigen','1')
                ->where('timestamp','>',$carbon)
                ->get();
//            dd($estadomaterial);
        //}

    }
    public function changeStatus($id)
    {
        $carbon = new Carbon();
        $carbon = $carbon::now();

        $material = reservas::find($id);

        $material->status = "1";
        $material->save();

        $reserva = new reserva_history();
        $reserva->op = $material->op;
        $reserva->linea = $material->linea;
        $reserva->maquina = $material->maquina;
        $reserva->feeder = $material->feeder;
        $reserva->pn = $material->pn;
        $reserva->lpn = $material->lpn;
//        $reserva->cantidad = $material->cantidad;
        $reserva->ubicacion = $material->ubicacion;
        $reserva->id_pedido = $material->id_pedido;
        $reserva->id_reserva = $material->id;

        $reserva->save();

        $material->lpn = $material->lpn." Entregado ".$carbon;
        $material->save();

        return redirect('amr/parciales/almacen')->with('message','LPN entregado correctamente');
    }
    public function cancelRequest($id)
    {
dd($id);
        $carbon = new Carbon();
        $carbon = $carbon::now();

        $material = reservas::find($id);

        $material->status = "1";
        $material->save();

        $reserva = new reserva_history();
        $reserva->op = $material->op;
        $reserva->linea = $material->linea;
        $reserva->maquina = $material->maquina;
        $reserva->feeder = $material->feeder;
        $reserva->pn = $material->pn;
        $reserva->lpn = $material->lpn;
//        $reserva->cantidad = $material->cantidad;
        $reserva->ubicacion = $material->ubicacion;
        $reserva->id_pedido = $material->id_pedido;
        $reserva->id_reserva = $material->id;

        $reserva->save();

        $material->lpn = $material->lpn." Cancelado ".$carbon;
        $material->save();

        return redirect('amr/parciales/almacen')->with('message','Pedido Cancelado');
    }
    public static function getDateInMysql($id)
    {
       $lpnDate = cgs_materialrequest::where('id',$id)
                ->get();
        foreach($lpnDate as $newDate)
        {
            $esto = GetWipOtInfo::tiempoAlmacen($newDate->timestamp);
        }
        return $esto;
    }
    public static function existInReserva($linea, $maquina, $feeder, $pn, $lpn,$id)
    {
        $data = array(
            "lpn"=>$lpn,
            "id"=>$id
        );

        $v = Validator::make($data, [
            'lpn' => 'required|unique:db2_tools.reservas,lpn',
            'id' => 'required|unique:db2_tools.reservas,id_pedido'
        ]);

        if ($v->fails()) {
            $messages = $v->errors();
//            dump($lpn,$id,$messages);
            return 0;
        }else{
//            dump($lpn,$id,'insertar');
            return 1;
        }
    }

    public static function lpnInDb2Tools($item_code)
    {
            return $lpns = ins_result::WHERE('field2',$item_code)
                ->where('id_instruction','13')
                ->orderby('field6','desc')
                ->get();
    }
    public function insertReserva($op,$linea, $maquina, $feeder, $pn, $lpn,$ubicacion,$id,$tiempopedido)
    {
            $reserva = new reservas();

            $reserva->op = $op;
            $reserva->linea = $linea;
            $reserva->maquina = $maquina;
            $reserva->feeder = $feeder;
            $reserva->pn = $pn;
            $reserva->lpn = $lpn;
//            $reserva->cantidad = $cantidad;
            $reserva->ubicacion = $ubicacion;
            $reserva->id_pedido = $id;
            $reserva->tiempopedido = $tiempopedido;
            $reserva->save();
    }
}