<?php

namespace IAServer\Http\Controllers\IAServer;

use Carbon\Carbon;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DebugPro extends Controller
{
    private $fileName = "";
    public $appendData = "";

    public function __construct($controller,$customLogFileName)
    {
        $this->fileName = $customLogFileName."_".Carbon::now()->toDateString();
        $this->monolog = $this->startLog($this->fileName);
    }

    public function clientInfo() {
        $ip = Request::server('REMOTE_ADDR');
        $host = getHostByAddr($ip);
        $message = array(
            "IP" => $ip,
            "Host" => $host,
            "Request Url" => Request::url(),
        );

        return join(' | ',$message);
    }

    private function startLog($filename)
    {
        $monolog = new Logger('CustomLog');
        $handler = new StreamHandler(storage_path('logs/'.$filename.'.log'), Logger::DEBUG);
        $handler->setFormatter(new LineFormatter(null,null,true,true));
        $monolog->pushHandler($handler);

        return $monolog;
    }

    public function runtime()
    {
        $duration = (microtime(true) - LARAVEL_START) ;
        $hours = (int) ($duration / 60 / 60);
        $minutes = (int) ($duration / 60) - $hours * 60;
        $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;
        $milliseconds = round($duration * 1000);
        $ram = Util::memoryUsed();

        $cliente = "Ram: ".$ram." | Runtime: ". $minutes ."m, ". $seconds."s, ". $milliseconds."ms | ". $this->clientInfo() . " | " .$this->appendData;


        $this->monolog->debug($cliente);
    }

    public function append($data)
    {
        $this->appendData .= $data;
    }

    function millitime() {
        $microtime = microtime();
        $comps = explode(' ', $microtime);

        // Note: Using a string here to prevent loss of precision
        // in case of "overflow" (PHP converts it to a double)
        return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
    }
}

