<?php

namespace IAServer\Http\Controllers\IAServer;

use Carbon\Carbon;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class Filter extends Controller
{
    public static function turnoSession()
    {
        $default = 'M';
        return self::makeSession('turno_session',$default);
    }

    public static function dateSession($sessionName="date_session")
    {
        $default = Carbon::now()->format('d-m-Y');
        return self::makeSession($sessionName,$default);
    }

    public static function makeSession($input_request_name,$default="")
    {
        $input_value = Input::get($input_request_name);
        if(is_null($input_value))
        {
            if(!Session::has($input_request_name))
            {
                Session::set($input_request_name, $default);
            }
        } else {
            Session::set($input_request_name, $input_value);
        }
        return Session::get($input_request_name);
    }
}
