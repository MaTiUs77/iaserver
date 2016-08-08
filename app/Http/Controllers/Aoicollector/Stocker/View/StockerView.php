<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\View;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Response;

class StockerView extends TrazaStockerView
{
    public function view_setStockerToAoi($stockerBarcode,$aoibarcode)
    {
        $produccion = Produccion::where('barcode',$aoibarcode)->first();
        $output = $this->addStocker($produccion,$stockerBarcode);
        return Response::multiple_output($output);
    }

    public function view_stockerControldeplacas($stockerBarcode)
    {
        $output = $this->stockerControldeplacas($stockerBarcode);
        return Response::multiple_output($output);
    }

    public function view_stockerInfo($stockerBarcode)
    {
        $stocker = $this->stockerInfoByBarcode($stockerBarcode);
        if(isset($stocker->op))
        {
            $smt = SMTDatabase::findOp($stocker->op);
        }

        $output =  compact('stocker','smt');
        return Response::multiple_output($output);
    }

    public function view_removeStocker($stockerBarcode)
    {
        $output = $this->removeStocker($stockerBarcode);
        return Response::multiple_output($output);
    }
}