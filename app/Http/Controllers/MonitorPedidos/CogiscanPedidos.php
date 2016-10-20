<?php

namespace IAServer\Http\Controllers\MonitorPedidos;

use Carbon\Carbon;
use IAServer\Http\Controllers\Email\Email;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\MonitorOp\GetWipOtInfo;
use IAServer\Http\Controllers\MonitorPedidos\Model\amr_deltamonitor;
use IAServer\Http\Controllers\MonitorPedidos\Model\cgs_materialrequest;
use IAServer\Http\Controllers\MonitorPedidos\Model\ins_result;
use IAServer\Http\Controllers\MonitorPedidos\Model\logs_pedidos;
use IAServer\Http\Controllers\MonitorPedidos\Model\pedidos_rechazados;
use IAServer\Http\Controllers\MonitorPedidos\Model\reserva_history;
use IAServer\Http\Controllers\MonitorPedidos\Model\reservas;
use IAServer\Http\Controllers\MonitorPedidos\Model\XXE_WIP_OT;
use IAServer\Http\Controllers\MonitorPedidos\Model\XXE_WMS_COGISCAN_PEDIDO_LPNS;
use IAServer\Http\Controllers\MonitorPedidos\Model\XXE_WMS_COGISCAN_WIP;
use IAServer\Http\Controllers\P2i\Model\Carga;
use Illuminate\Http\Request;
use IAServer\Http\Controllers\P2i\Model\General;
use IAServer\Http\Controllers\MonitorPedidos\Model\XXE_WMS_COGISCAN_PEDIDOS;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
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
                ->WHERE('PEDIDOS.STATUS','<>','PROCESSED')
                ->WHERE('PEDIDOS.STATUS','<>','NEW')
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
        $carbon = Carbon::yesterday();

            if(trim($item_code) != NULL){

                return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
                    ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
                    ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
                    ->WHERE('PEDIDOS.STATUS','PROCESSED')
                    ->WHERE('PEDIDOS.ITEM_CODE',$item_code)
                    ->WHERE('PEDIDOS.LAST_UPDATE_DATE','>',$carbon)
                    ->ORDERBY('PEDIDOS.LINEA_ID','DESC')
                    ->paginate(60);
            }
            else {
                return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
                    ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
                    ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
                    ->WHERE('PEDIDOS.STATUS','PROCESSED')
                    ->WHERE('PEDIDOS.LAST_UPDATE_DATE','>',$carbon)
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
        $newInsert->linDest = "0";
        $newInsert->ubicacionOrigen = "5";
        $newInsert->status = "127";
        $newInsert->delta = "0";
        $newInsert->insertId = "0";
        $newInsert->PROD_LINE = strtoupper($request->input('prod_line'));
        $newInsert->MAQUINA = strtoupper($request->input('maquina'));
        $newInsert->UBICACION = strtoupper($request->input('ubicacion'));
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
                ->where('id_instruction','13')
                ->orderby('tiempopedido','desc')
                ->distinct('id_pedido')
                ->get();
        }else
        {
            return reservas::where('timestamp', '>', $carbon)
                ->where('status','=',0)
                ->where('id_instruction','13')
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
                ->where('id_instruction','13')
                ->orderby('tiempopedido','desc')
                ->distinct('id_pedido')
                ->get();
        }else
        {
            return reservas::where('timestamp', '>', $carbon)
                ->where('status','=',0)
                ->where('id_instruction','13')
                ->orderby('tiempopedido', 'desc')
                ->distinct('id_pedido')
                ->get();

        }
    }
    public function showMaterialMysql()
    {

        $carbon = new Carbon();
        $carbon = Carbon::today();

        $id = reservas::orderby('id_pedido','desc')
            ->first();

        if($id != null)
        {
            return $estadomaterial = cgs_materialrequest::where('id','>',$id->id_pedido)
                ->whereIn('ubicacionOrigen',[1,3])
                ->where('timestamp','>',$carbon)
                ->get();

        }
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
//      $reserva->cantidad = $material->cantidad;
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
            return 1;
        }
    }
    public function getRequestXLpn($id)
    {
        return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
            ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
            ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
            ->WHERE('PEDIDOS.INSERT_ID',$id)
            ->ORDERBY('PEDIDOS.LINEA_ID','DESC')
            ->get();
    }
    public static function lpnInDb2Tools($item_code,$maquina,$linea,$ubicacion,$op,$id,$cant)
    {
        $string = substr($maquina,0,-8);

        if($item_code == null)
            {
                return false;

            }else {

            $lpns = ins_result::WHERE('field2', $item_code)
                ->where('field5','LIKE','%'.$string.'%')
                ->where('id_instruction',15)
                ->orderby('field6', 'desc')
                ->get();

            if($lpns->isEmpty())
            {

                $lpns1 = ins_result::WHERE('field2', $item_code)
                    ->where('id_instruction',13)
                    ->orderby('field6', 'desc')
                    ->get();
                if($lpns1->isEmpty())
                {
                    $ifExistInLogs = logs_pedidos::WHERE('INSERT_ID',$id)
                        ->GET();

                    if($ifExistInLogs->isEmpty())
                    {
//                    $newUpdate = cgs_materialrequest::find($id);
//                    $newUpdate->ubicacionOrigen = "2";
//                    $newUpdate->status = "2";
//
//
//                    $newUpdate->save();

//                    $newid = $newUpdate->id;
//
//                    $newrequest = new XXE_WMS_COGISCAN_PEDIDOS();
//
//                    $newrequest->OP_NUMBER = strtoupper($op);
//                    $newrequest->ORGANIZATION_CODE = "UP3";
//                    $newrequest->OPERATION_SEQ = "1";
//                    $newrequest->ITEM_CODE = strtoupper($item_code);
//                    $newrequest->ITEM_UOM_CODE = "EA";
//                    $newrequest->QUANTITY = strtoupper($cant);
//                    $newrequest->PROD_LINE = strtoupper($linea);
//                    $newrequest->MAQUINA = strtoupper($maquina);
//                    $newrequest->UBICACION = strtoupper($ubicacion);
//                    $newrequest->STATUS = "NEW";
//                    $newrequest->INSERT_ID = $newid;
//
//                    $newrequest->save();


                        $logs = new logs_pedidos();

                        $logs->OP_NUMBER = strtoupper($op);
                        $logs->ORGANIZATION_CODE = "UP3";
                        $logs->OPERATION_SEQ = "1";
                        $logs->ITEM_CODE = strtoupper($item_code);
                        $logs->ITEM_UOM_CODE = "EA";
                        $logs->QUANTITY = strtoupper($cant);
                        $logs->PROD_LINE = strtoupper($linea);
                        $logs->MAQUINA = strtoupper($maquina);
                        $logs->UBICACION = strtoupper($ubicacion);
                        $logs->STATUS = "NEW";
                        $logs->INSERT_ID = $id;

                        $logs->save();

                        return $lpns = collect([]);
                    }
                    else
                    {
                        return $lpns = collect([]);
                    }

                }else{
                    return $lpns1;
                }
            }else{
                return $lpns;
            }
        }
    }
    public function getIdRequestXLpn($lpn)
    {
        return cgs_materialrequest::where('rawMaterial',$lpn)
            ->first();

    }
    public function getRequestXidReservas($id)
    {
        return reservas::where('id_pedido',$id)
            ->get();
    }
    public function insertReserva($op,$linea, $maquina, $feeder, $pn, $lpn,$ubicacion,$id,$tiempopedido, $id_instruction)
    {
        $reserva = new reservas();

        if($id_instruction == 13)
        {
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
            $reserva->id_instruction = $id_instruction;

            $reserva->save();
        }else
        {


            $carbon = new Carbon();
            $carbon = $carbon::now();

            $reserva->op = $op;
            $reserva->linea = $linea;
            $reserva->maquina = $maquina;
            $reserva->feeder = $feeder;
            $reserva->pn = $pn;
            $reserva->lpn = $lpn ." ". $carbon;
//            $reserva->cantidad = $cantidad;
            $reserva->ubicacion = $ubicacion;
            $reserva->id_pedido = $id;
            $reserva->tiempopedido = $tiempopedido;
            $reserva->id_instruction = $id_instruction;

            $reserva->save();
        }

    }
    public function getRequestXLinea($prod_line)
    {
        $carbon = new Carbon();
        $carbon = Carbon::yesterday();
        if(trim($prod_line) != NULL){

            return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
                ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
                ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
                ->WHERE('PEDIDOS.STATUS','PROCESSED')
                ->WHERE('PEDIDOS.PROD_LINE',$prod_line)
                ->WHERE('PEDIDOS.LAST_UPDATE_DATE','>',$carbon)
                ->ORDERBY('PEDIDOS.LINEA_ID','DESC')
                ->paginate(60);
        }
        else
        {
            return "ingrese alguna linea";
        }
    }
    public function getRequestPartNumber($partnumber,$columna)
    {

        if($columna != null)
        {
            return cgs_materialrequest::SELECT(DB::RAW('CGS.*, STATUS.*'))
                ->FROM('cgs_materialrequest as CGS')
                ->LEFTJOIN('cgs_status as STATUS','STATUS.idStatus','=','CGS.ubicacionOrigen')
                ->where('CGS.'.$columna,'=',$partnumber)
                ->take(10)
                ->orderby('CGS.timestamp','desc')
                ->get();
        }else{
            return cgs_materialrequest::orderBy('timestamp','desc')
            ->take(0)
            ->get();
        }
    }
    public function getRequestDeltaMonitor($partnumber)
    {
        return amr_deltamonitor::where('rawMaterialId',$partnumber)
            ->take(10)
            ->orderBy('timeStampRegistro','desc')
            ->get();
    }
    public function getDeliveryPartNumber($partnumber)
    {
        return reservas::where('pn',$partnumber)
            ->orderby('tiempopedido','desc')
            ->get();
    }
    public function getRequestTransito()
    {
        $carbon = new Carbon();
        $carbon =  Carbon::today();
            return reservas::where('id_instruction', 15)
                ->where('timestamp', '>', $carbon)
                ->orderby('id_pedido', 'desc')
                ->get();
    }
    public function getRequestTransitoReserva($linea)
    {
        $carbon = new Carbon();
        $carbon = Carbon::today();

        return reservas::where('id_instruction', 15)
            ->where('linea',$linea)
            ->where('timestamp', '>', $carbon)
            ->orderby('id_pedido', 'desc')
            ->get();
    }
    public function getTrazaPedidos($insert_id)
    {

        return cgs_materialrequest::where('id',$insert_id)
            ->orderby('id','desc')
            ->get();
    }
    public function trazabilidad($id)
    {

        $item_code = XXE_WMS_COGISCAN_PEDIDOS::where('INSERT_ID',$id)
            ->get();
        $partnumber = $item_code->FIRST()->ITEM_CODE;

        return $result = XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('CGS_PEDIDOS.INSERT_ID
       ,CGS_WIP.QUANTITY_PER_ASSEMBLY
       ,CGS_WIP.REQUIRED_QUANTITY
       ,CGS_WIP.QUANTITY_ISSUED
       ,WIP_OT.START_QUANTITY
       ,WIP_OT.QUANTITY_COMPLETED
       ,WIP_OT.SEGMENT1
       ,WIP_OT.DESCRIPTION'))
            ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS CGS_PEDIDOS')
            ->LEFTJOIN('XXE_WMS_COGISCAN_WIP AS CGS_WIP','CGS_PEDIDOS.OP_NUMBER','=','CGS_WIP.OP_NUMBER')
            ->LEFTJOIN('XXE_WIP_OT AS WIP_OT','CGS_PEDIDOS.OP_NUMBER','=', 'WIP_OT.WIP_ENTITY_NAME')
            ->WHERE('CGS_PEDIDOS.INSERT_ID','=',$id)
            ->WHERE('CGS_WIP.MATERIAL','=',$partnumber)
            ->GET();

    }
    public static function pedidosRechazados($rechazos)
    {

        foreach ($rechazos as $r)
        {
            $pedido = pedidos_rechazados::WHERE('INSERT_ID',$r->INSERT_ID)
                    ->get();
            if($pedido->isEmpty()) {
                $newinsert = new pedidos_rechazados();
                $newinsert->OP_NUMBER = $r->OP_NUMBER;
                $newinsert->ORGANIZATION_CODE = $r->ORGANIZATION_CODE;
                $newinsert->OPERATION_SEQ = $r->OPERATION_SEQ;
                $newinsert->ITEM_CODE = $r->ITEM_CODE;
                $newinsert->ITEM_UOM_CODE = $r->ITEM_UOM_CODE;
                $newinsert->QUANTITY = $r->QUANTITY;
                $newinsert->QUANTITY_ASSIGNED = $r->QUANTITY_ASSIGNED;
                $newinsert->PROD_LINE = $r->PROD_LINE;
                $newinsert->MAQUINA = $r->MAQUINA;
                $newinsert->UBICACION = $r->UBICACION;
                $newinsert->STATUS = $r->STATUS;
                $newinsert->ERROR_MESSAGE = $r->ERROR_MESSAGE;
                $newinsert->LAST_UPDATE_DATE = $r->LAST_UPDATE_DATE;
                $newinsert->INSERT_ID = $r->INSERT_ID;

                $newinsert->save();

                $mail = new Email();
                $mail->send("AMR","josemaria.casarotto@newsan.com.ar","ATENCION: Pedido Rechazado->".$r->ITEM_CODE,['data'=>$r],"emails.pedidorechazado");
                $mail->send("AMR","adrianaisabel.vidal@newsan.com.ar","ATENCION: Pedido Rechazado->".$r->ITEM_CODE,['data'=>$r],"emails.pedidorechazado");
                $mail->send("AMR","claudia.garcia@newsan.com.ar","ATENCION: Pedido Rechazado->".$r->ITEM_CODE,['data'=>$r],"emails.pedidorechazado");
                $mail->send("AMR","mirtadelina.castillo@newsan.com.ar","ATENCION: Pedido Rechazado->".$r->ITEM_CODE,['data'=>$r],"emails.pedidorechazado");
                $mail->send("AMR","diego.maidana@newsan.com.ar","ATENCION: Pedido Rechazado->".$r->ITEM_CODE,['data'=>$r],"emails.pedidorechazado");
            }
        }
        return redirect(url('/amr/parciales'));
    }
}