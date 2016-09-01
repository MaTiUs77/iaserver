<?php
namespace IAServer\Http\Controllers\SMTDatabase\Componentes;

use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\SMTDatabase\Model\Materiales;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;

class ComponentesView extends Controller
{
    public function findComponente() {
        $componente = Input::get('componente');
        $materiales = Materiales::findComponente($componente)->get();

        $output = compact('materiales','componente');
        return view('smtdatabase.componentes.buscar',$output);
    }

    public function findSemielaborado() {
        $componente = Input::get('componente');
        $materiales = Materiales::findSemielaborado($componente)->get();

        $output = compact('materiales','componente');
        return view('smtdatabase.componentes.buscar',$output);
    }

    public function allSemielaboradoByModelo() {
        $modelo = Input::get('modelo');
        $materiales = Materiales::allSemielaboradoByModelo($modelo)->get();

        $output = compact('materiales','modelo');
        return view('smtdatabase.componentes.semi_por_modelo',$output);
    }
}
