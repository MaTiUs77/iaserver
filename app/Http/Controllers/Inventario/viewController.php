<?php

namespace IAServer\Http\Controllers\Inventario;

use Illuminate\Routing\Controller;
//use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Response;

class viewController extends Controller
{
    public function updateLabel()
    {
        $etiqueta = new invController();
        $label = $etiqueta->findlabel();

        $output = compact('label');
        return Response::multiple_output($output, 'inventario.editar');
    }
}