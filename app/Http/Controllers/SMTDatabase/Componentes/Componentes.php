<?php
namespace IAServer\Http\Controllers\SMTDatabase\Componentes;

use IAServer\Http\Controllers\SMTDatabase\Model\Materiales;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;

class Componentes extends SMTDatabase
{
    public static function buscarComponente($componente) {
        $materiales = Materiales::modelsWithComponente($componente)->get();
        $output = compact('materiales','componente');
        return $output;
    }
}
