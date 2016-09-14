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
        /*try
        {
            $onRedis = \LRedis::get($stockerBarcode.':info');

            if($onRedis == null)
            {*/
                //------------------ Datos de stocker  ---------------------
                $output = $this->findStocker($stockerBarcode);
                if(isset($output->stocker->op))
                {
                    $output->smt = SMTDatabase::findOp($output->stocker->op);
                }
                //-----------------------------------------------------------
                /*
                if(isset($output->stocker->op))
                {
                    \LRedis::set($output->stocker->barcode.':info', json_encode($output));
                }
            } else
            {
                $output = json_decode($onRedis);
            }
        } catch(\Exception $e)
        {
            return response()->view('errors.exception', ['mensaje'=> "Error al ejecutar Redis"], 500);
        }*/

        return Response::multiple_output($output);
    }

    public function view_stockerInfoDeclared($stockerBarcode)
    {
        /*
        try
        {
            $onRedis = \LRedis::get($stockerBarcode.'.declared');

            if($onRedis == null)
            {*/
                //--------------- Datos de stocker declarado ----------------
                $output = $this->findStocker($stockerBarcode);
                if(isset($output->stocker))
                {
                    $output->smt =  SMTDatabase::findOp($output->stocker->op);
                    $output->contenido = $this->stockerDeclaredDetail($output->stocker);
                } else
                {
                    $output->error = 'El codigo de stocker no existe';
                }

                //-----------------------------------------------------------
/*
                if($output->detalle != null)
                {
                    if($output->detalle->stocker_declarado)
                    {
                        \LRedis::set($output->stocker->barcode.'.declared', json_encode($output));
                        \LRedis::expire($output->stocker->barcode, 50);
                    }
                }
            } else
            {
                $output = json_decode($onRedis);
            }
        } catch(\Exception $e)
        {
            return response()->view('errors.exception', ['mensaje'=> "Error al ejecutar Redis"], 500);
        }*/

        return Response::multiple_output($output);
    }

    public function view_removeStocker($stockerBarcode)
    {
        $output = $this->removeStocker($stockerBarcode);
        return Response::multiple_output($output);
    }
}
