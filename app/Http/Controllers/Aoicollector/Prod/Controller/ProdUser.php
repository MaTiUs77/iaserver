<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod\Controller;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class ProdUser extends Controller
{
    public function login($userId,$userName,$aoibarcode)
    {
        $output = array();

        $user = User::where('id',$userId)->where('name',$userName)->first();

        if(isset($user->id)) {
            $user = Auth::loginUsingId($user->id,true);
            if ($user->hasProfile()) {
                $user->fullname = $user->profile->fullname();
            } else
            {
                $user->fullname = $user->name;
            }

            $prod = Produccion::where('barcode',$aoibarcode)->first();

            if(isset($prod->id))
            {
                if($prod->id_user == $user->id)
                {
                    $prod->id_user = null;

                } else
                {
                    $prod->id_user = $user->id;
                }

                $prod->save();
            }

            $output = $user;
        }else{
            $output = array('error'=>'Usuario de acceso desconocido');
        }

        return $output;
    }

    public function logout($userId,$userName,$aoibarcode) {
        $user = User::where('id',$userId)->where('name',$userName)->first();
        $output = array('error'=>'No se encuentra logueado');

        if(isset($user->id)) {
            $prod = Produccion::where('barcode',$aoibarcode)
                ->where('id_user',$user->id)
                ->first();

            if(isset($prod->id))  {
                if($prod->id_user == $user->id)  {
                    $prod->id_user = null;
                    $prod->save();
                }
            }
        }

        return $output;
    }
}