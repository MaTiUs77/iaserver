<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\Controller;

use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\WipSerie;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LavadoController extends Controller
{
    public function index()
    {
        Auth::attempt(['name' => Input::get('name'), 'password' => Input::get('password')]);

        if(Input::get('logout'))
        {
            Auth::logout();
        }

        if (Input::get('find')) {
            $stocker = Stocker::vista()
                ->where('barcode', Input::get('find'))
                ->first();

            if ($stocker != null) {
                $stocker->sendToRouteId(7);
                $stocker->liberar();
            }
        }

        $stockers = Stocker::vista()
            ->where('id_stocker_route', 7)
            ->get();

        $output = compact('stocker', 'stockers');
        return view('aoicollector.stocker.lavado.index', $output);
    }

    public function etiquetar()
    {
        $barcode = Input::get('stk');

        $stocker = Stocker::vista()
            ->where('barcode',$barcode)
            ->first();

        $output = compact('stocker');

        return view('aoicollector.stocker.lavado.etiquetar', $output);
    }

    public function imprimir($barcode)
    {
        $etiquetas = new EtiquetasController();
        $output = $etiquetas->zebraPrint($barcode);
        dd($output);

        return view('aoicollector.stocker.lavado.index',$output);
    }
}
