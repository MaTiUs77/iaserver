<?php

namespace IAServer\Http\Controllers\Ipc;

use Carbon\Carbon;
use IAServer\Http\Controllers\Auth\Entrust\Role;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Ipc\Model\Certificacion;

use IAServer\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class CertificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|ipc_admin,ipc_instructor',['except' => 'index']);
    }

    public function index()
    {
        $certificacion = Certificacion::orderBy('id_perfil')->orderBy('fecha_baja')->get();

        $output = compact('certificacion');

        return Response::multiple_output($output,'ipc.certificacion.index');
    }

    public function create()
    {
        $roles = Role::with('users')->where('name', 'ipc_instructor')->get()->first();

        $instructores = array();
        if(isset($roles->users)) {
            $instructores = $roles->users;
        }
        return view('ipc.certificacion.create',compact('instructores'));
    }

    public function store()
    {
        $rules = array(
            'id_norma'  => 'required|numeric',
            'certificado' => 'required',
            'fecha_alta' => 'required',
            'id_instructor' => 'required|numeric'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('ipc/certificacion/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $store = new Certificacion();

            $store->id_norma = Input::get('id_norma');
            $store->codigo_certificado = Input::get('certificado');
            $store->id_instructor = Input::get('id_instructor');
            $store->fecha_alta = Util::dateToEn(Input::get('fecha_alta'));
            $store->fecha_baja = Carbon::createFromFormat('Y-m-d', $store->fecha_alta)->addYears(2)->lastOfMonth()->format('Y-m-d');

            $store->modo = 'certificacion';
            $store->id_perfil = Input::get('id_perfil');

            $store->save();

            return redirect('ipc/certificacion/create')->with('message','Registro creado con exito!');
        }
    }

    public function destroy($id)
    {
        $message = 'Eliminado con exito!';
        $el = Certificacion::where('id_certificacion',$id);
        if($el) {
            $el->delete();
        } else {
            $message = 'El elemento no existe!';
        }

        return redirect('ipc/certificacion')->with('message',$message);
    }
}
