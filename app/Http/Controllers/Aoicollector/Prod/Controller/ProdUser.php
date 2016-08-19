<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod\Controller;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ProdUser extends Controller
{
    public function login($userId,$userName)
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

            $prod = Produccion::where('barcode',Input::get('aoibarcode'))->first();
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
            $output = array('error'=>'Usuario/Password desconocido');
        }

        return $output;
    }

    public function logout(){
        Auth::logout();
        $output = array('done'=>'logout');

        return $output;
    }
}