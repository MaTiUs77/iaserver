<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\View;

use IAServer\Events\RedisSend;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Redis\RedisController;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Response;

class StockerView extends TrazaStockerView
{
    public function view_setStockerToAoi($stockerBarcode,$aoibarcode)
    {
        $produccion = Produccion::where('barcode',$aoibarcode)->first();
        $output = $this->addStocker($produccion,$stockerBarcode);
        return Response::multiple($output);
    }

    public function view_stockerControldeplacas($stockerBarcode)
    {
        $output = $this->stockerControldeplacas($stockerBarcode);
        return Response::multiple($output);
    }

    public function view_stockerInfo($stockerBarcode)
    {
        $output = $this->findStocker($stockerBarcode);
        if(isset($output->stocker->op))
        {
            $output->smt = SMTDatabase::findOp($output->stocker->op);
        }

        return Response::multiple($output);
    }

    public function view_stockerInfoDeclared($stockerBarcode)
    {
        $stockerBarcode = strtoupper($stockerBarcode);
        $output = $this->findStocker($stockerBarcode);

        if (isset($output->stocker))
        {
            $output->smt = SMTDatabase::findOp($output->stocker->op);
            $output->contenido = $this->stockerDeclaredDetail($output->stocker);
        } else
        {
            $output->error = 'El codigo de stocker no existe';
        }

        return Response::multiple($output);
    }

    public function view_removeStocker($stockerBarcode)
    {
        $output = $this->removeStocker($stockerBarcode);
        return Response::multiple($output);
    }
}
