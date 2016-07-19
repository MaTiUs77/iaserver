<?php

namespace IAServer\Http\Controllers\P2i;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\P2i\Model\Carga;

use IAServer\Http\Controllers\P2i\Model\General;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CargaController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|p2i_operador',['except' => 'index']);
    }

    public function index()
    {
        Filter::dateSession();
        $filtro_fecha = Util::dateToEn(Session::get('date_session'));

        $proceso = Carga::whereNull('tiempo_proceso')->where('fecha',$filtro_fecha)->orderBy('id','desc')->get();
        $finalizado = Carga::whereNotNull('tiempo_proceso')->where('fecha',$filtro_fecha)->orderBy('id','desc')->get();

        $output = compact('proceso','finalizado');

        return Response::multiple_output($output,'p2i.carga.index');
    }

    public function create()
    {
        $cantidad_camaras  = General::where('variable','cantidad_camaras')->first()->valor;

        return view('p2i.carga.create',compact('cantidad_camaras'));
    }

    public function store()
    {
        $rules = array(
            'ciclo'  => 'required|numeric',
            'monomero'  => 'required|numeric',
            'camara' => 'required|numeric',
            'conjunto_jigs' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('p2i/carga/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store

            $store = new Carga();

            $monomero_start                 = Input::get('monomero_start');

            $store->ciclo                    = Input::get('ciclo');
            $store->camara                   = Input::get('camara');

            $store->monomero                 = Input::get('monomero');
            $store->conjunto_jigs            = Input::get('conjunto_jigs');
            $store->observacion              = Input::get('observacion');

            $store->limp_camara              = Input::has('limp_camara') ? true:false;
            $store->limp_laminas_laterales   = Input::has('limp_laminas_laterales') ? true:false;
            $store->limp_burlete_puerta      = Input::has('limp_burlete_puerta') ? true:false;
            $store->jigs_cargados            = Input::has('jigs_cargados') ? true:false;
            $store->nivel_monomero           = Input::has('nivel_monomero') ? true:false;
            $store->verif_filtros            = Input::has('verif_filtros') ? true:false;

            $store->fecha = Carbon::now()->toDateString();
            $store->hora_entrada = Carbon::now()->toTimeString();

            $store->id_operador = Auth::user()->id;

            if(is_numeric($monomero_start))
            {
                $store->monomero_start = $monomero_start;
                $store->save();
            } else {
                $store->save();
                $store->monomero_start = $store->id;
                $store->save();
            }

            return redirect('p2i/carga')->with('message','Registro creado con exito!');
        }
    }

    public function destroy($id)
    {
        $message = 'Eliminado con exito!';
        $el = Carga::find($id);
        if($el) {
            $el->delete();
        } else {
            $message = 'El elemento no existe!';
        }

        return redirect('p2i/carga')->with('message',$message);
    }

    public function lastMonomero($camara)
    {
        $output = Carga::where('camara',$camara)->orderBy('id','desc')->first();

        return Response::multiple_output($output);
    }

    public function terminarProceso($id_carga)
    {
        $el = Carga::find($id_carga);
        $el->hora_salida = Carbon::now()->toTimeString();

        // DEBERIA SALIR EL MISMO DIA
        $entrada = $el->fecha . " ".$el->hora_entrada;
        $salida  = $el->fecha . " ".$el->hora_salida;

        $tiempo_proceso = Carbon::createFromFormat('Y-m-d H:i:s', $entrada)->diff(Carbon::createFromFormat('Y-m-d H:i:s', $salida));

        $el->tiempo_proceso = $tiempo_proceso->h.'h, '.$tiempo_proceso->i.'m';
        $el->save();

        return redirect('p2i/carga')->with('message','Proceso finalizado con exito!');
    }

    public function monomeroStat() {
        $query = "
        SELECT
            MIN(fecha) as monomero_start_date,
            MAX(fecha) as monomero_end_date,
            camara,
            monomero,
            monomero_start,
            count(monomero) as monomero_usos

        FROM p2i.carga

        group by camara,monomero,monomero_start
        order by monomero_start asc
        ";

        $stat = DB::connection('iaserver')->select($query);

        $output = compact('stat');

        return Response::multiple_output($output,'p2i.carga.stat');
    }
}
