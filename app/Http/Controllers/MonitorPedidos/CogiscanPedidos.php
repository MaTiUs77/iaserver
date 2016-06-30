<?php

namespace IAServer\Http\Controllers\MonitorPedidos;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\MonitorPedidos\Model\cgs_materialrequest;
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
        if(trim($item_code) != NULL){

            return XXE_WMS_COGISCAN_PEDIDOS::SELECT(DB::RAW('PEDIDOS.*,PEDIDOS_LPNS.LPN,PEDIDOS_LPNS.LPN_QUANTITY'))
                ->FROM('XXE_WMS_COGISCAN_PEDIDOS AS PEDIDOS')
                ->LEFTJOIN('XXE_WMS_COGISCAN_PEDIDO_LPNS AS PEDIDOS_LPNS','PEDIDOS_LPNS.LINEA_ID','=','PEDIDOS.LINEA_ID')
                ->WHERE('PEDIDOS.STATUS','ERROR')
                ->WHERE('PEDIDOS.ITEM_CODE',$item_code)
                ->ORDERBY('PEDIDOS.LINEA_ID','DESC')
                ->paginate(60);
        }
        else {
            return XXE_WMS_COGISCAN_PEDIDOS::WHERE('STATUS','<>', 'NEW')
                ->WHERE('STATUS','<>','PROCESSED')
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
                    ->WHERE('PEDIDOS.LAST_UPDATE_DATE','=',$carbon)
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
    public function showMaterialMysql($partnumber)
    {

        $carbon = new Carbon();
        $carbon = Carbon::today();
        if(trim($partnumber) != NULL)
        {
            return $estadomaterial = cgs_materialrequest::select(DB::raw("cgs_materialrequest.*, cgs_status.estadoUbicacion"))
                ->FROM('cgs_materialrequest')
                ->LEFTJOIN("cgs_status", "cgs_status.idStatus", '=', 'cgs_materialrequest.ubicacionOrigen')
                ->where('cgs_status.estadoUbicacion', '=', 'almacen')
                ->where('cgs_materialrequest.codMat',$partnumber)
                ->where('cgs_materialrequest.timestamp','>',$carbon)
                ->orderby('timestamp', 'desc')
                ->paginate(60);
        }
        else
        {
            return $estadomaterial = cgs_materialrequest::select(DB::raw("cgs_materialrequest.*, cgs_status.estadoUbicacion"))
                ->FROM('cgs_materialrequest')
                ->LEFTJOIN("cgs_status", "cgs_status.idStatus", '=', 'cgs_materialrequest.ubicacionOrigen')
                ->where('cgs_status.estadoUbicacion', '=', 'almacen')
                ->where('cgs_materialrequest.timestamp','>',$carbon)
                ->orderby('timestamp', 'desc')
                ->paginate(60);
        }

    }
    public function changeStatus($id)
    {
        $material = cgs_materialrequest::find($id);

        $material->ubicacionOrigen = "4";
        $material->save();

        return redirect('amr/parciales/almacen');
    }
    public function getDateInMysql($item_lpn)
    {
       $lpnDate = CogiscanPedidos::where('rawMaterial',$item_lpn)
            ->get();
        $esto = $this->changeStatus($lpnDate->timestamp);
        return $esto;
    }
}