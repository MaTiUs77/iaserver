<?php

namespace IAServer\Http\Controllers\Inventario;

use Carbon\Carbon;
use IAServer\Http\Controllers\Email\Email;
use IAServer\Http\Controllers\Inventario\Model\impresiones;
use IAServer\Http\Controllers\Inventario\Model\users;
use IAServer\Http\Controllers\Inventario\Model\sector;
use IAServer\Http\Controllers\Inventario\Model\planta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class invController extends Controller
{

    public static function findlabel($id)
    {

        return $res = impresiones::SELECT(DB::RAW('lpn.lpn
                ,i.id_etiqueta
                ,i.id_responsable_imp
                ,i.id_partnumber
                ,i.id_zona
                ,i.cant_agregada
                ,i.seg_conteo
                ,i.ter_conteo
                ,i.fecha_impresion
                ,i.id_planta
                ,m.descripcion
                ,m.codigo
                ,m.unidad_medida
                ,s.descripcion as descripcionZona'))
            ->FROM('impresiones as i')
            ->LEFTJOIN('materiales as m','i.id_partnumber','=','m.codigo')
            ->LEFTJOIN('lpn_generator as lpn','lpn.id','=','i.id_etiqueta')
            ->LEFTJOIN('sector as s','i.id_zona','=','s.id_sector')
            ->WHERE('lpn.lpn',$id)
            ->GET();

    }
    public function updateLabel($id,$qty,$qty2,$qty3)
    {


        $updateLabel = impresiones::find($id);
        $updateLabel->cant_agregada = $qty;
        $updateLabel->seg_conteo = $qty2;
        $updateLabel->ter_conteo = $qty3;
//        dd($updateLabel);
        $updateLabel->save();

    }
    public static function userInfo($user_id)
    {
        return users::SELECT(DB::RAW('
        u.user,
        u.descripcion,
        cu.id_sector,
        cu.id_planta,
        cu.id_impresora,
        pc.printer_address,
        pc.id_printer_type,
        pc.setdarkness,
        pc.velocidad_impresion'))
            ->from('config_user as cu')
            ->leftjoin('users as u','u.id_user', '=', 'cu.id_user')
            ->leftjoin('printer_config as pc','pc.id_printer_config', '=', 'cu.id_impresora')
            ->where('user_id',$user_id)->get();
    }
    public static function lpn_gen($id)
    {

    }
    public static function getPlants()
    {
        return planta::all();
    }

    public static function getSectors()
    {
        return sector::all();
    }

}