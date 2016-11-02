<?php

namespace IAServer\Http\Controllers\Inventario;

use Carbon\Carbon;
use IAServer\Http\Controllers\Email\Email;
use IAServer\Http\Controllers\Inventario\Model\impresiones;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class invController extends Controller
{

    public function findlabel()
    {

       return $res = impresiones::SELECT(DB::RAW('i.id_impresion
        ,m.codigo
        ,s.descripcion as descripcionZona
        ,i.id_responsable_imp
        ,i.cant_agregada
        ,i.seg_conteo
        ,i.ter_conteo
        ,cu.id_ip
        ,m.desc_u_medida
        ,m.descripcion
        ,cu.id_sector
        ,cu.id_planta'))
            ->FROM('impresiones as i')
            ->LEFTJOIN('materiales as m','i.id_partnumber','=','m.id_material')
            ->LEFTJOIN('sector as s','i.id_zona','=','s.id_sector')
            ->LEFTJOIN('users as u','i.id_responsable_imp','=','u.user')
            ->LEFTJOIN('config_user as cu','u.id_user','=','cu.id_user')
            ->WHERE('i.id_impresion',20015)
            ->GET();
    }

}