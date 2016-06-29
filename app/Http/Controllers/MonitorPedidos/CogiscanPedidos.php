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
    public function getRequestPartial($item_code)
    {
            if(trim($item_code) != NULL){

                return $op = XXE_WMS_COGISCAN_PEDIDOS::where('ITEM_CODE',$item_code)
//                    ->where('STATUS','<>','NEW')
//                    ->where('STATUS','<>','PROCESSED')
                    ->orderby ('LAST_UPDATE_DATE','DESC')
                    ->paginate(25);
            }
            else {
                return XXE_WMS_COGISCAN_PEDIDOS::WHERE('STATUS','<>', 'NEW')
                    ->WHERE('STATUS','<>','PROCESSED')
                    ->orderby ('LAST_UPDATE_DATE','DESC')
                    ->paginate(25);
            }
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
        if(trim($partnumber) != NULL)
        {
            return $estadomaterial = cgs_materialrequest::select(DB::raw("cgs_materialrequest.*, cgs_status.estadoUbicacion"))
                ->FROM('cgs_materialrequest')
                ->LEFTJOIN("cgs_status", "cgs_status.idStatus", '=', 'cgs_materialrequest.ubicacionOrigen')
                ->where('cgs_status.estadoUbicacion', '=', 'almacen')
                ->where('cgs_materialrequest.codMat',$partnumber)
                ->orderby('timestamp', 'desc')
                ->paginate(25);
        }
        else
        {
            return $estadomaterial = cgs_materialrequest::select(DB::raw("cgs_materialrequest.*, cgs_status.estadoUbicacion"))
                ->FROM('cgs_materialrequest')
                ->LEFTJOIN("cgs_status", "cgs_status.idStatus", '=', 'cgs_materialrequest.ubicacionOrigen')
                ->where('cgs_status.estadoUbicacion', '=', 'almacen')
                ->orderby('timestamp', 'desc')
                ->paginate(25);
        }

    }
}