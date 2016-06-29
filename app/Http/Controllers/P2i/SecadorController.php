<?php

namespace IAServer\Http\Controllers\P2i;

use Carbon\Carbon;
use IAServer\Http\Controllers\P2i\Model\General;
use IAServer\Http\Controllers\P2i\Model\Modelo;
use IAServer\Http\Controllers\P2i\Model\Secador;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class SecadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|operador_p2i',['except' => 'index']);
    }

    public function index()
    {
        $proceso = Secador::whereNull('tiempo_proceso')->orderBy('id','desc')->get();
        $finalizado = Secador::whereNotNull('tiempo_proceso')->orderBy('id','desc')->get();

        return view('p2i.secador.index',compact('proceso','finalizado'));
    }

    public function create()
    {
        $modelos = Modelo::all();
        $cantidad_secadores  = General::where('variable','cantidad_secadores')->first()->valor;
        $cantidad_camaras  = General::where('variable','cantidad_camaras')->first()->valor;
        return view('p2i.secador.create',compact('modelos','cantidad_camaras','cantidad_secadores'));
    }

    public function store()
    {
        $rules = array(
            'id_modelo' => 'required|numeric',
            'secador' => 'required_if:mode,1|numeric',
            'camara' => 'required_if:mode,0|numeric',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('p2i/secador/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $store = new Secador();

            $store->id_modelo =         Input::get('id_modelo');
            $store->secador =           Input::get('secador');
            $store->jigs_cargados =     Input::has('jigs_cargados') ? true:false;
            $store->fecha =             Carbon::now()->toDateString();
            $store->hora_entrada =      Carbon::now()->toTimeString();

            $store->conjunto_jigs            = Input::get('conjunto_jigs');
            $store->observacion              = Input::get('observacion');

            $store->id_operador = Auth::user()->id;

            $store->save();

            return redirect('p2i/secador')->with('message','Registro creado con exito!');
        }
    }

    public function destroy($id)
    {
        $message = 'Eliminado con exito!';
        $el = Secador::find($id);
        if($el) {
            $el->delete();
        } else {
            $message = 'El elemento no existe!';
        }

        return redirect('p2i/secador')->with('message',$message);
    }

    public function terminarProceso($id)
    {
        $el = Secador::find($id);
        $el->hora_salida = Carbon::now()->toTimeString();

        // DEBERIA SALIR EL MISMO DIA
        $entrada = $el->fecha . " ".$el->hora_entrada;
        $salida  = $el->fecha . " ".$el->hora_salida;

        $tiempo_proceso = Carbon::createFromFormat('Y-m-d H:i:s', $entrada)->diff(Carbon::createFromFormat('Y-m-d H:i:s', $salida));

        $el->tiempo_proceso = $tiempo_proceso->h.'h, '.$tiempo_proceso->i.'m';
        $el->save();

        return redirect('p2i/secador')->with('message','Proceso finalizado con exito!');
    }
}
