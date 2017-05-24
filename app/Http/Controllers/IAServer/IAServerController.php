<?php

namespace IAServer\Http\Controllers\IAServer;

use IAServer\Http\Controllers\IAServer\Model\Menu;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class IAServerController extends Controller
{
    public function index()
    {
        $root = IAServerController::IAServerMenu();
        $output = compact('root');
        return view('iaserver.home', $output);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->back();
    }

    public function attemptLogin()
    {
        Auth::attempt(['name' => Input::get('name'), 'password' => Input::get('password')]);
        return redirect()->back();
    }

    public static function IAServerMenu()
    {
        $all_menu = Menu::orderBy('titulo','asc')->get();

        $root = array();
        foreach($all_menu as $menu)
        {
            if(self::menuAccess($menu->permiso))
            {
                $root[$menu->id] = $menu;
                $root[$menu->id]['submenu'] = array_filter(iterator_to_array($all_menu), function($m) use($menu) {
                    if($m->root == $menu->id) {
                        if(self::menuAccess($m->permiso)) {
                            return $m;
                        }
                    }
                });
            }
        }

        return $root;
    }

    public static function menuAccess($permisos) {
        // Por defecto no se imprime el menu root
        $print = false;

        // Verifico permisos del menu
        $permisosToArray = explode(',',$permisos);

        // Si el menu no requiere permisos lo muestro
        if($permisos==null) {
            $print = true;
        } else {
            // El menu requiere permisos, verifico si el usuario dispone de los mismos
            if(Auth::user() && Auth::user()->hasRole($permisosToArray))
            {
                $print = true;
            } else
            {
                // No esta autenticado o no tiene permisos... oculto menu
                $print = false;
            }
        }

        return $print;
    }

    public function logo()
    {
        return view('iaserver.logo');
    }

    public function prompter()
    {
        return view('iaserver.common.prompt');
    }
}
