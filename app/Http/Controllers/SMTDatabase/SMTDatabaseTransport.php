<?php
namespace IAServer\Http\Controllers\SMTDatabase;

use IAServer\Http\Controllers\Aoicollector\Model\Panel;
use IAServer\Http\Controllers\SMTDatabase\Model\Ingenieria;
use IAServer\Http\Controllers\SMTDatabase\Model\Lotes;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SMTDatabaseTransport extends Controller
{
    public static function handle($op, $transport_to_op = false)
    {
        $smt = SMTDatabase::findOp($op);

        if($smt!=null) {
            $transport = Panel::where('inspected_op',$op)->count();
            $panels = OrdenTrabajo::listPanelsByModeloLote($smt->modelo, $smt->lote);
        }

        if($transport_to_op)
        {
            $smtTo = SMTDatabase::findOp($transport_to_op);

            if($smtTo!=null) {
                $quantity = Panel::where('inspected_op',$transport_to_op)->count();
            }
        }

        $output = compact('op','smt','panels','transport','smtTo','quantity');
        return $output;
    }

    public static function transport()
    {
        $transport_from = Input::get('transport_from');
        $transport_to = Input::get('transport_to');

        Panel::where('inspected_op',$transport_from)->update(['inspected_op' => $transport_to]);

        return self::handle($transport_from,$transport_to);
    }
}
