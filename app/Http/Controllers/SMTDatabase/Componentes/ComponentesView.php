<?php
namespace IAServer\Http\Controllers\SMTDatabase\Componentes;

use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;

class ComponentesView extends Componentes
{
    public static function buscar() {
        $componente = Input::get('componente');
        $output = self::buscarComponente($componente);
        return view('smtdatabase.componentes.buscar',$output);
    }
}
