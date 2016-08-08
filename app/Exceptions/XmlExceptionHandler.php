<?php

namespace IAServer\Exceptions;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class XmlExceptionHandler extends Exception
{
    public function __construct($tolog, $message="", $code=0, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);

        $ip = Request::server('REMOTE_ADDR');
        $host = getHostByAddr(Request::server('REMOTE_ADDR'));
        $messageArray = array(
            "Exception" => get_class($this),
            "File" => get_class($this),
            "User" => (Auth::user()) ? Auth::user()->name : 'No logueado',
            "IP" => $ip,
            "Host" => $host,
            "Request Url" => Request::url(),
            "Code" =>  $code,
            "Message" => $message,
            "CustomMessage" => $tolog
        );

        $messageOutput = "";
        foreach($messageArray as $key => $value){
            $messageOutput .= $key.' ---> '.$value."\n";
        }

        Log::error($messageOutput);
    }
}
