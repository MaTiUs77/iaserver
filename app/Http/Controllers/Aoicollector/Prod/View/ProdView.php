<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod\View;

use IAServer\Http\Controllers\Aoicollector\Prod\Controller\ProdUser;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

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
        $user = new ProdUser();
        return $user->login($userId,$userName);
    }

    public Function userLogout(){
        $user = new ProdUser();
        $user->logout();
        return redirect(route('aoicollector.prod.index'));
    }
}