<?php
namespace IAServer\Http\Controllers\SMTDatabase;

use IAServer\Http\Controllers\SMTDatabase\Model\Ingenieria;
use IAServer\Http\Controllers\SMTDatabase\Model\Lotes;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

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

    public static function syncSmtWithWip($smt,$wip)
    {
        // Verifica si existe alguna actualizacion en la cantidad de la OP y la actualiza en SMTDatabase
        if($wip!=null && $wip->wip_ot != null && $smt!=null)
        {
            if($smt->semielaborado != $wip->wip_ot->codigo_producto)
            {
                $smt->semielaborado = $wip->wip_ot->codigo_producto;
                $smt->save();
            }

            if(((int)$smt->qty != (int)$wip->wip_ot->start_quantity) && $wip->wip_ot->start_quantity!=null)
            {
                $smt->qty = $wip->wip_ot->start_quantity;
                $smt->save();
            }
        }
    }
}
