<?php

namespace IAServer\Exceptions;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class CogiscanExceptionHandler extends Exception
{
    public function __construct($tolog, $message="", $code=0, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);

        /*$ip = Request::server('REMOTE_ADDR');
        $host = getHostByAddr(Request::server('REMOTE_ADDR'));
        $messageArray = array(
            "User" => (Auth::user()) ? Auth::user()->name : 'No logueado',
            "Class" => get_class($this),
            "IP" => $ip,
            "Host" => $host,
            "Request Url" => Request::url(),
        );

        Log::critical("========================= CogiscanExceptionHandler =========================");
        Log::critical(join(' | ',$messageArray));
        Log::critical("Message: " .$message);
        Log::critical("Code: " .$code);
        Log::critical("Output: ". $tolog);*/
    }
}
