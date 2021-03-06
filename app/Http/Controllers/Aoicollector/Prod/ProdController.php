<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\RouteOp;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Controllers\Trazabilidad\Sfcs\Sfcs;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ProdController extends Controller
{
    public function aoiProductionInfo($aoibarcode,$first=false)
    {
        $prod = (object) Produccion::fullInfo($aoibarcode);

        return Response::multiple($prod);
    }

    public function opInfo($op,$aoibarcode="")
    {
        $op = trim(strtoupper($op));
        $w = new Wip();
        $wip = $w->findOp($op,false);
        $routeop = RouteOp::where('op',$op)->get();
        $smt = SMTDatabase::findOp($op);

        SMTDatabase::syncSmtWithWip($smt,$wip);

        $sfcs = new Sfcs();
        $sfcs = $sfcs->puestosOp($op);

        $output = compact('aoibarcode','op','wip','smt','routeop','sfcs');
        return Response::multiple($output,'aoicollector.prod.partial.infoop');
    }

    public function opInfoSubmit()
    {
        $var = (object) Input::all();

        $prod = Produccion::where('barcode',$var->aoibarcode)->first();

        if(isset($prod->id))
        {
            $prod->op = $var->op;
            $prod->id_stocker = null;
            $prod->id_route_op = null;
            $prod->manual_mode = 0;

            if(isset($var->id_route_op))
            {
                $prod->id_route_op = $var->id_route_op;
                $prod->manual_mode = $var->id_modo;
            } else
            {
                $prod->line_id = $var->line_id;
                $prod->puesto_id = $var->puesto_id;
                $prod->modelo_id = $var->modelo_id;
            }

            $prod->save();
        }

        return redirect(route('aoicollector.prod.index'));
    }

    public function opRemove($aoibarcode)
    {
        $prod = Produccion::where('barcode',$aoibarcode)->first();

        if(isset($prod->id))
        {
            $prod->op = null;
            $prod->line_id = null;
            $prod->puesto_id = null;
            $prod->modelo_id = null;
            $prod->id_stocker =null;
            $prod->id_route_op =null;

            $prod->save();
        }

        return redirect(route('aoicollector.prod.index'));
    }


}