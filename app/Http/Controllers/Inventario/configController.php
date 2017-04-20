<?php

namespace IAServer\Http\Controllers\Inventario;

use IAServer\Http\Controllers\Inventario\Model\printer_config;
use IAServer\Http\Controllers\Inventario\Model\printer_type;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class configController extends Controller
{
    /* ---------------ABM IMPRESORAS--------------- */
    public function index($id = null)
    {
        if($id == null)
        {
            return printer_config::orderBy('id_printer_config','desc')->get();
        }else{
            return $this->showPrinter($id);
        }
    }
    public function addPrinter(Request $request)
    {
        $newPrinter = new printer_config();
        $newPrinter->printer_address = $request->input('printer_address');
        $newPrinter->id_printer_type = $request->input('id_printer_type');
        $newPrinter->setdarkness = $request->input('setdarkness');
        $newPrinter->velocidad_impresion = $request->input('velocidad_impresion');

       $newPrinter->save();

        return "Impresora agregada correctamente";
    }
    public function showPrinter($id)
    {
        return printer_config::where('id_printer_config',$id)->get();
    }
    public function configuracion()
    {
        return view('inventario.configuracion.configuracion');
    }
    public function updatePrinter(Request $request, $id)
    {

        $updatePrinter = printer_config::where('id_printer_config',$id)->first();

        $updatePrinter->printer_address = $request->input('printer_address');
        $updatePrinter->id_printer_type = $request->input('id_printer_type');
        $updatePrinter->setdarkness = $request->input('setdarkness');
        $updatePrinter->velocidad_impresion = $request->input('velocidad_impresion');

        $updatePrinter->save();

        return "Impresora actualizada correctamente";
    }
    public function deletePrinter($id)
    {

        $deletePrinter = printer_config::where('id_printer_config',$id)->delete();

        return "Impresora borrada correctamente";
    }
    public function getPrinterType()
    {
        $printer =  printer_type::where('id_printer_type',1);
    }
    /* ---------------FIN ABM IMPRESORAS--------------- */


}