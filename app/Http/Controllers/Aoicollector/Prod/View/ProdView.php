<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod\View;

use IAServer\Events\RedisSend;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Prod\Controller\ProdUser;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class ProdView extends Controller
{
    public function index()
    {
        return view('aoicollector.prod.index');
    }

    public function userLogin()
    {
        $userId = Input::get('userid');
        $userName = Input::get('name');
        $aoibarcode  = Input::get('aoibarcode');

        $user = new ProdUser();
        return $user->login($userId,$userName,$aoibarcode);
    }

    public function userLogout(){
        $userId = Input::get('userid');
        $userName = Input::get('name');
        $aoibarcode  = Input::get('aoibarcode');

        $user = new ProdUser();
        return $user->logout($userId,$userName,$aoibarcode);
        //return redirect(route('aoicollector.prod.index'));
    }
}