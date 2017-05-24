<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\Lavado;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerTraza;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\EtiquetasController;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class LavadoController extends Controller
{
    public function index()
    {
        $stockers = Stocker::vista()
            ->where('id_stocker_route', 7)
            ->whereDate('created_at', '=', Carbon::today()->toDateString())
            ->get();

        $lavados = $this->lavadosPorDia();
        $output = compact('stocker', 'stockers','lavados');
        return view('aoicollector.stocker.lavado.index', $output);
    }

    public function search() {

        $barcode = Input::get('stkbarcode');

        $historial = StockerTraza::select(DB::raw("
                stk.barcode,
                stkr.name,
                stkt.*
            "))
            ->from('aoidata.stocker_traza as stkt')
            ->join('aoidata.stocker as stk', DB::raw('stk.id'), '=', DB::raw('stkt.id_stocker'))
            ->join('aoidata.stocker_route as stkr', DB::raw('stkr.id'), '=', DB::raw('stkt.id_stocker_route'))

            ->whereRaw("stkt.id_stocker_route = 7")
            ->whereRaw("stk.barcode = '$barcode'")
            ->orderBy(DB::raw("stkt.created_at"),'desc')
            ->get();

        $output = compact('historial');

        return view('aoicollector.stocker.lavado.search', $output);
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

    public function lavadosPorDia()
    {
        $lavados = StockerTraza::select(DB::raw('
            COUNT(id_stocker_route) as lavados,
            DATE(created_at) AS fecha
        '))->
        where('id_stocker_route',7)
        ->groupBy(DB::raw('DATE(created_at)'))->get();

        return $lavados;
    }
}
