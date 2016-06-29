<?php
namespace IAServer\Http\Controllers\SMTDatabase;

use IAServer\Http\Controllers\Aoicollector\Model\Panel;
use IAServer\Http\Controllers\SMTDatabase\Model\Ingenieria;
use IAServer\Http\Controllers\SMTDatabase\Model\Lotes;
use IAServer\Http\Controllers\SMTDatabase\Model\Materiales;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SMTDatabase extends Controller
{
    public static function listaDeIngenieria()
    {
        $op = self::findOp('OP-107832');
        $ingenieria = Ingenieria::where('modelo',$op->modelo)
            ->where('lote',$op->lote)
            ->first();

        $lista = Lotes::where('id_ingenieria',$ingenieria->id)
            ->where('id_ver',$ingenieria->version)
            ->get();

        dd($op,$ingenieria,$lista);
    }

    public static function findOp($op)
    {
        $sql = OrdenTrabajo::where('op',$op)->first();
        return $sql;
    }
}
