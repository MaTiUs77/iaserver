<?php

namespace IAServer\Http\Controllers\Controldeplacas;

use IAServer\Http\Controllers\Controldeplacas\Model\Datos;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Debug\Debug;

class Controldeplacas extends Controller
{
    public $debug;

    public function __construct()
    {
        //$this->debug = new Debug($this);
    }

    public function index() {
        $id_sector = 2; // Automatica

        $datos = (object) DatosController::salidaByOp($id_sector,['fecha'=>'now']);
        return view('controldeplacas.index', compact('datos'));
    }

    public function viewFiltrarForm()
    {
        return view('controldeplacas.partial.modal_filtrar');
    }

    public function filtrar()
    {
        $modelo = Input::get('modelo');
        $op = Input::get('op');
        $lote = '';
        $panel = '';
        $destino = '';
        $estado = '';
        $desde = '';
        $hasta = '';

        $id_sector = 2; // Automatica
        $datos = (object) DatosController::salidaByOp($id_sector,[
            'op'=> $op,
            'fecha' => 'all'
        ]);
        return view('controldeplacas.index', compact('datos'));
    }
}
