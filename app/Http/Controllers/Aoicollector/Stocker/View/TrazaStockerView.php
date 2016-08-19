<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\View;

use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\TrazaStocker;
use IAServer\Http\Requests;

class TrazaStockerView extends TrazaStocker
{
    // Localiza un stocker o un panel segun el elemento enviado
    public function view_findElement($element="")
    {
        $find = $this->findElement($element);
        $detalle = $this->stockerDeclaredDetail($find->stocker);

        $output = compact('find','detalle');

        return view('trazabilidad.stocker.index', $output);
    }

    // Localiza un stocker segun su barcode
    public function view_findStocker($barcode)
    {
        $output = $this->findStocker($barcode);


        return view('trazabilidad.stocker.index', $output);
    }
}
