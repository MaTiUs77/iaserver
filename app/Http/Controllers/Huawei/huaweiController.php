<?php

namespace IAServer\Http\Controllers\Huawei;

use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\Huawei\Model\imei_nro_serie;
use IAServer\Http\Controllers\Huawei\Model\imei_nro_serie_no_encontrados;
use IAServer\Http\Controllers\Huawei\Model\prod_transfer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Files\ExcelFile;
use Symfony\Component\HttpFoundation\Request;


class huaweiController extends Controller
{
    public function index ()
    {

//        Excel::load('public\lpn.xlsx', function($result){
//            $result = $result->get();
//            foreach($result as $key => $row)
//            {
////                echo $row->numero_serie."\n";
//                $rest = prod_transfer::where('NRO_SERIE',$row->numero_serie)->first();
//                dd($row->numero_serie);
//                if(!empty($rest))
//                {
//                    $insert = new imei_nro_serie();
//                    $insert->COMPANIA = $row->compania;
//                    $insert->PRODUCTO = $row->producto;
//                    $insert->DESCRIPCION = $row->descripcion;
//                    $insert->MARCA = $row->marca;
//                    $insert->NUMERO_SERIE = $row->numero_serie;
//                    $insert->LPN = $row->lpn;
//                    $insert->IMEI= $rest->REFERENCIA_1;
//                    $insert->OC= $row->oc;
//                    $insert->REMITO = $row->remito;
//                    $insert->FACTURA = $row->factura;
//                    $insert->FECHA_FACTURA = $row->fecha_factura->format('d-m-Y');
//                    $insert->PRECIO_PEDIDO = $row->precio_pedido;
//                    $insert->DIVISA_PEDIDO = $row->divisa_pedido;
//                    $insert->TIPO_DE_CAMBIO_PEDIDO = $row->tipo_de_cambio_pedido;
//                    $insert->PRECIO_FACTURA = $row->precio_factura;
//                    $insert->DIVISA_FACTURA = $row->divisa_factura;
//                    $insert->save();
//                }
//                else{
//
//                    $insert = new imei_nro_serie_no_encontrados();
//                    $insert->COMPANIA = $row->compania;
//                    $insert->PRODUCTO = $row->producto;
//                    $insert->DESCRIPCION = $row->descripcion;
//                    $insert->MARCA = $row->marca;
//                    $insert->NUMERO_SERIE = $row->numero_serie;
//                    $insert->LPN = $row->lpn;
//                    $insert->IMEI= "-";
//                    $insert->OC= $row->oc;
//                    $insert->REMITO = $row->remito;
//                    $insert->FACTURA = $row->factura;
//                    $insert->FECHA_FACTURA = $row->fecha_factura->format('d-m-Y');
//                    $insert->PRECIO_PEDIDO = $row->precio_pedido;
//                    $insert->DIVISA_PEDIDO = $row->divisa_pedido;
//                    $insert->TIPO_DE_CAMBIO_PEDIDO = $row->tipo_de_cambio_pedido;
//                    $insert->PRECIO_FACTURA = $row->precio_factura;
//                    $insert->DIVISA_FACTURA = $row->divisa_factura;
//                    $insert->save();
//
//                }
//            }
//            echo "TERMINADO";
//
//        })->get();
    }
}
