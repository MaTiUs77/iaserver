<?php
namespace IAServer\Http\Controllers\SMTDatabase\Componentes;

use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;

class ComponentesView extends Componentes
{
    public static function findComp() {
        $componente = Input::get('componente');
        $output = self::buscarComponente($componente);
        return view('smtdatabase.componentes.buscar',$output);
    }

    public static function findSemi() {
        $componente = Input::get('componente');
        $output = self::buscarSemielaborado($componente);
        return view('smtdatabase.componentes.buscar',$output);
    }
}
