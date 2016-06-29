<?php

namespace IAServer\Http\Controllers\P2i;

use Carbon\Carbon;
use IAServer\Http\Controllers\P2i\Model\General;
use IAServer\Http\Controllers\P2i\Model\Limpieza;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class LimpiezaController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|operador_p2i',['except' => 'index']);
    }

    public function index()
    {
        $el = Limpieza::all();

        if(Request::ajax()) {
            return response()->json($el);
        }

        return view('p2i.limpieza.index')->with('limpieza',$el);
    }

    public function create()
    {
        $cantidad_secadores  = General::where('variable','cantidad_secadores')->first()->valor;
        $cantidad_camaras  = General::where('variable','cantidad_camaras')->first()->valor;
        return view('p2i.limpieza.create',compact('cantidad_camaras','cantidad_secadores'));
    }

    public function store()
    {
        $rules = array(
            'ciclo' => 'required|numeric',
            'secador' => 'required_if:mode,1|numeric',
            'camara' => 'required_if:mode,0|numeric',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('p2i/limpieza/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $store= new Limpieza();

            if(Input::has('camara') && Input::get('mode') == 0)
            {
                $store->camara = Input::get('camara');
            }

            if(Input::has('secador') && Input::get('mode') == 1)
            {
                $store->secador = Input::get('secador');
            }

            $store->ciclo =                         Input::get('ciclo');
            $store->aspirado_camara =               Input::has('aspirado_camara') ? true:false;
            $store->limp_laminas_laterales =        Input::has('limp_laminas_laterales') ? true:false;
            $store->limp_burlete_puerta =           Input::has('limp_burlete_puerta') ? true:false;
            $store->verif_rejilla_monomero =        Input::has('verif_rejilla_monomero') ? true:false;
            $store->aspirado_rejillas_laterales =   Input::has('aspirado_rejillas_laterales') ? true:false;
            $store->verif_dummies =                 Input::has('verif_dummies') ? true:false;
            $store->limp_jigs =                     Input::has('limp_jigs') ? true:false;
            $store->limp_p2i_y_secador =            Input::has('limp_p2i_y_secador') ? true:false;
            $store->presion_helio =                 Input::has('presion_helio') ? true:false;

            $store->fecha = Carbon::now()->toDateString();
            $store->hora = Carbon::now()->toTimeString();

            $store->save();

            return redirect('p2i/limpieza')->with('message','Registro creado con exito!');
        }
    }

    public function destroy($id)
    {
        $message = 'Eliminado con exito!';
        $el = Limpieza::find($id);
        if($el) {
            $el->delete();
        } else {
            $message = 'El elemento no existe!';
        }

        if(Request::ajax()) {
            return response()->json(['message'=>$message]);
        }

        return redirect('p2i/limpieza')->with('message',$message);
    }
}
