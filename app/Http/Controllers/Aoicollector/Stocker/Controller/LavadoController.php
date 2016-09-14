<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\Controller;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class LavadoController extends Controller
{
    public function index()
    {
        Auth::attempt(['name' => Input::get('name'), 'password' => Input::get('password')]);

        if(Input::get('logout'))
        {
            Auth::logout();
        }

        $stockers = Stocker::vista()
            ->where('id_stocker_route', 7)
            ->whereDate('created_at', '=', Carbon::today()->toDateString())
            ->get();

        $output = compact('stocker', 'stockers');
        return view('aoicollector.stocker.lavado.index', $output);
    }

    public function finishClean($barcode)
    {
        if($barcode!='')
        {
            $stocker = Stocker::vista()
                ->where('barcode',$barcode)
                ->first();

            if ($stocker != null) {
                $stocker->sendToRouteId(7);
                $stocker->liberar();
            }
        }

        $output = compact('stocker');
        return $output;
    }

    public function etiquetar()
    {
        $barcode = Input::get('stk');

        if($barcode!='')
        {
            $stocker = Stocker::vista()
                ->where('barcode',$barcode)
                ->first();
        }

        $output = compact('stocker');

        return view('aoicollector.stocker.lavado.etiquetar', $output);
    }

    public function imprimir($barcode,$qty)
    {
        $etiquetas = new EtiquetasController();
        $output = $etiquetas->zebraPrint($barcode,$qty);

        return $output;
//        return view('aoicollector.stocker.lavado.index',$output);
    }
}
