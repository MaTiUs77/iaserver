<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\View;

use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\TrazaStocker;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;

set_time_limit(1000);


class TrazaStockerView extends TrazaStocker
{
    // Localiza un stocker o un panel segun el elemento enviado
    public function view_findElement($element="")
    {
        $find = $this->findElement($element);

        if(isset($find->stocker))
        {
            $contenido = $this->stockerDeclaredDetail($find->stocker);
        }

        $output = compact('find','contenido');

        return view('trazabilidad.stocker.index', $output);
    }

    // Localiza un stocker segun su barcode
    public function view_findStocker($barcode)
    {
        $output = $this->findStocker($barcode);
        return view('trazabilidad.stocker.index', $output);
    }

    public function view_findStockerPocketPc($stockerBarcode="")
    {
        if(empty($stockerBarcode))
        {
            $stockerBarcode = Input::get('element');
        }
        $find = $this->findStocker($stockerBarcode);

        if(isset($find->stocker))
        {
            $detalle = $this->stockerDeclaredDetail($find->stocker);
        }

        $output = compact('stockerBarcode','find','detalle');

        return view('trazabilidad.stocker.pocket', $output);
    }

    // Localiza un stocker segun su barcode
    public function view_rastrearOpView($op="")
    {
        $output = $this->rastrearOp($op);
        return view('trazabilidad.stocker.rastrear_op.index', $output);
    }

    public function view_changeOp()
    {
        $stockerBarcode = Input::get('stockerBarcode');
        $toOp = Input::get('toOp');

        $output = $this->changeOp($stockerBarcode,$toOp);
    }
}
