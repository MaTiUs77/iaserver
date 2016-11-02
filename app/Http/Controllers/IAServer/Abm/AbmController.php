<?php

namespace IAServer\Http\Controllers\IAServer\Abm;

use IAServer\Http\Controllers\Auth\Entrust\Profile;
use IAServer\Http\Controllers\Auth\Entrust\Role;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AbmController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $users = User::all();

        return view('iaserver.abm.index',compact('users'));
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $roles = Role::orderBy('display_name','asc')->get();
        $user = User::find($id);
        return view('iaserver.abm.edit',compact('roles','user'));
    }

    public function create()
    {
        $roles = Role::orderBy('display_name','asc')->get();
        return view('iaserver.abm.create',compact('roles'));
    }

    public function store()
    {
        $rules = array(
            'name'  => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'permiso' => 'required|numeric',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('abm/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $user = new User();

            $user->name = Input::get('name');
            $user->password = bcrypt(Input::get('password'));
            $user->save();

            $profile = new Profile();
            $profile->nombre = Input::get('nombre');
            $profile->apellido = Input::get('apellido');

            $user->profile()->save($profile);

            $user->attachRole(Input::get('permiso'));

            return redirect('abm')->with('message','Usuario creado con exito!');
        }
    }

    public function update($id)
    {
        $rules = array(
            'nombre' => 'required',
            'apellido' => 'required',
            'permiso' => 'numeric',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('abm/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $user = User::find($id);

            if(Input::get('password')!='')
            {
                $user->password = bcrypt(Input::get('password'));
                $user->save();
            }

            $user->profile->nombre = Input::get('nombre');
            $user->profile->apellido = Input::get('apellido');
            $user->profile->save();

            if(is_numeric(Input::get('permiso')) && Input::get('permiso')> 0)
            {
                $user->attachRole(Input::get('permiso'));
            }

            return redirect('abm')->with('message','Usuario actualizado con exito!');
        }
    }

    public function destroy($id)
    {
        $message = 'Usuario eliminado con exito!';
        $el = User::find($id);
        if($el) {
            $el->delete();
        } else {
            $message = 'El usuario no existe!';
        }

        return redirect('abm')->with('message',$message);
    }
}
