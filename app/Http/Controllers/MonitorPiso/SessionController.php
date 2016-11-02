<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use Illuminate\Http\Request;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function getSession()
    {
        $user = Auth::user();
        return $user;
    }
}
