<?php
namespace IAServer\Http\Controllers\SMTDatabase\Componentes;

use IAServer\Http\Controllers\SMTDatabase\Model\Materiales;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;

class Componentes extends SMTDatabase
{
    public static function buscarComponente($componente) {
        $materiales = Materiales::findComponent($componente)->get();
        $output = compact('materiales','componente');
        return $output;
    }

    public static function buscarSemielaborado($componente) {
        $materiales = Materiales::findSemielaborado($componente)->get();

        $output = compact('materiales','componente');
        return $output;
    }
}
