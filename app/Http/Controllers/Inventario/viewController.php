<?php

namespace IAServer\Http\Controllers\Inventario;

use IAServer\Http\Controllers\Inventario\Model\lpn_generator;
use Illuminate\Routing\Controller;
//use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Response;

class viewController extends Controller
{
    public function index()
    {
     return view('inventario.configuracion.configuracion');
    }

    public function vistaImprimir()
    {
        return view('inventario.imprimir');
    }

    public function vistaConfiguracion()
    {
        return view('inventario.configuracion.configuracion');
    }

    public function vistaConsultar()
    {
        return view('inventario.consultar');
    }

    public function updateLabel()
    {
        $etiqueta = new invController();
        $label = $etiqueta->findlabel();

        $output = compact('label');
        return Response::multiple($output, 'inventario.editar');
    }

    public function vistaReportes()
    {
        return view('inventario.consulta.consulta');
    }
    public function plantas()
    {

        $plantas = new invController();
        $pl = $plantas->getPlants();

        $output = compact('pl');
        return Response::multiple($output,'inventario.consulta.consulta');
    }
}