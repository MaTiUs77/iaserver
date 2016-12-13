<?php

namespace IAServer\Http\Controllers\Inventario;

use IAServer\Http\Controllers\Auth\Entrust\Role;
use IAServer\Http\Controllers\Inventario\Model\config_user;
use IAServer\Http\Controllers\Inventario\Model\role_user;
use IAServer\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;

class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrInv =[];
        $allUsers = User::all();
        foreach($allUsers  as $user)
        {
            $roles = $user->roles;
            foreach ($roles as $rol)
            {
                if ($rol->name == 'admin')
                {
                    $rol->class = 'success';
                }
                else
                {
                    $rol->class = 'info';
                }
            }
            $perfil = $user->profile;
            $inventario = $user->inventario;
            if($inventario != null) {
                $sector = $inventario->joinSector;
                $planta = $inventario->joinPlanta;
                $impresora = $inventario->joinImpresora;
                array_push($arrInv,
                    [
                        "perfil" => [
                            "user_id" => $user->id,
                            "username" => $user->name,
                            "nombre" => $perfil->nombre,
                            "apellido" => $perfil->apellido,
                            "rol" => $roles
                        ],
                        "config_user" => [
                            "id_config" => $inventario->id_config,
                            "id_sector" => $sector->id_sector,
                            "sector" => $sector->descripcion,
                            "id_planta" => $planta->id_planta,
                            "planta" => $planta->descripcion,
                            "impresora" => [
                                "id_impresora" => $impresora->id_printer_config,
                                "printer_address" => $impresora->printer_address,
                                "id_printer_type" => $impresora->id_printer_type,
                                "setdarkness" => $impresora->setdarkness,
                                "velocidad_impresion" => $impresora->velocidad_impresion
                            ]
                        ]
                    ]);
            }
        }
        return $arrInv;
    }

    public function getSessionData()
    {
        $session = Auth::user();
        return $session;
    }
    public function getProfileData($id)
    {
        $usuario = User::find($id);
        $perfil = $usuario->profile;
        return $perfil;
    }

    public function showiaserver()
    {
        return User::doesntHave('inventario')->get();
    }

    public function showUsers()
    {
        return view('inventario.configuracion.usuarios');
    }

    public function showProfile()
    {
        return view('inventario.configuracion.perfil_de_usuario');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $input = Input::all();
        $config_user = new config_user();
        $config_user->user_id = $input['perfil']['user_id'];
        $config_user->id_sector = $input['config_user']['id_sector'];
        $config_user->id_planta = $input['config_user']['id_planta'];
        $config_user->id_impresora = $input['config_user']['impresora']['id_impresora'];
        try{
            $config_user->save();
            return 'exito';
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $roles = $user->roles;
        foreach ($roles as $rol)
        {
            if ($rol->name=='admin')
            {
                $rol->class = "danger";
            }
            else
            {
                $rol->class = "info";
            }
        }
        $perfil = $user->profile;
        $inventario = $user->inventario;
        $sector = $inventario->joinSector;
        $planta =$inventario->joinPlanta;
        $impresora = $inventario->joinImpresora;
        $arrInv = array(
            "perfil"=>[
                "user_id"=>$user->id,
                "username"=>$user->name,
                "nombre"=>$perfil->nombre,
                "apellido"=>$perfil->apellido,
                "rol"=>$roles
            ],
            "config_user"=>[
                "id_config"=>$inventario->id_config,
                "id_sector"=>$sector->id_sector,
                "sector"=>$sector->descripcion,
                "id_planta"=>$planta->id_planta,
                "planta"=>$planta->descripcion,
                "impresora"=>[
                    "id_impresora"=>$impresora->id_printer_config,
                    "printer_address"=>$impresora->printer_address,
                    "id_printer_type"=>$impresora->id_printer_type,
                    "setdarkness"=>$impresora->setdarkness,
                    "velocidad_impresion"=>$impresora->velocidad_impresion
                ]
            ]
        );
        return $arrInv;
    }

    public function getRoles($id)
    {
        return role_user::SELECT(DB::RAW('
        ru.user_id,
        ru.role_id,
        r.name
        '))
        ->from('role_user as ru')
        ->leftjoin('roles as r','r.id','=','ru.role_id')
        ->where('ru.user_id','=',$id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
//        $id = Input::get('user_name');
        $perfil = $request->get('perfil');
        $config_user = $request->get('config_user');
        $user = User::find($perfil['user_id']);
        $inventario = $user->inventario;
        $inventario->id_sector = $config_user['id_sector'];
        $inventario->id_planta = $config_user['id_planta'];
        $inventario->id_impresora = $config_user['impresora']['id_impresora'];
        try{
            $inventario->save();
            return 'exito';
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
    }
}
