<?php

namespace IAServer\Http\Controllers\IAServer;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Debug extends Controller
{
    public $microtime;
    public $debugName = "";

    private $queryDebug = false;
    public $monolog = null;

    private $lastValue = '';

    public function __construct($controller,$query_debug=false,$customLogFileName=null,$header=true)
    {
        $this->queryDebug = $query_debug;
        if($this->microtime==null) {
            $this->microtime = microtime(true);
        }

        if($customLogFileName==null)
        {
            Log::debug("");
            Log::debug("==========================================================================================");
            if(Auth::user()) { Log::debug("User: ".Auth::user()->name); }
            Log::debug("Class: ".get_class($controller));
            Log::debug("QueryDebug: ".(($this->queryDebug) ? 'true' : 'false'));
            Log::debug("==========================================================================================");
        } else
        {
            $this->monolog = $this->customLog($customLogFileName);

            if($header!=null)
            {
                $this->monolog->debug("==========================================================================================");
                if(Auth::user()) { $this->monolog->debug("User: ".Auth::user()->name); }
                $this->monolog->debug("Class: ".get_class($controller));
                $this->monolog->debug("QueryDebug: ".(($this->queryDebug) ? 'true' : 'false'));
                $this->monolog->debug("==========================================================================================");
            }
                  }

        if($this->queryDebug) {
            if($this->monolog==null)
            {
                Event::listen('illuminate.query',function($query,$params,$time,$conn){
                    Log::debug(array($query,$params,$time,$conn));
                });
            } else
            {
                Event::listen('illuminate.query',function($query,$params,$time,$conn){
                    $this->monolog->debug('Query',(array($query,$params,$time,$conn)));
                });
            }
        }
    }

    public function appendLog($value)
    {
        if($this->monolog==null)
        {
            Log::debug($value);
        } else
        {
            $this->monolog->debug($value);
        }
    }

    public function put($value='')
    {
        if($this->monolog==null)
        {
            Log::debug($value);
        } else
        {
            $this->monolog->debug($value);
        }

        $this->lastValue = $value;
    }
    private function customLog($filename)
    {
        $monolog = new Logger('CustomLog');

        $handler = new StreamHandler(storage_path('logs/'.$filename.'.log'), Logger::DEBUG);
        $handler->setFormatter(new LineFormatter(null,null,true,true));
        $monolog->pushHandler($handler);

        return $monolog;
    }

    public function __destruct()
    {
        $this->finish();
    }

    private function finish()
    {
        $microtime = $this->lastValue ." *** ProccessTime: ".$this->microtimeToHuman(microtime(true),$this->microtime)." ";
        if($this->monolog==null)
        {
            Log::debug($microtime);
        } else
        {
            $this->monolog->debug($microtime);
        }
    }

    private function microtimeToHuman($endTime,$startTime)
    {
        $duration = $endTime - $startTime;
        $hours = (int) ($duration / 60 / 60);
        $minutes = (int) ($duration / 60) - $hours * 60;
        $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;

        return ($hours == 0  ? "00":$hours) . ":" .($minutes == 0 ? "00":($minutes < 10 ? "0".$minutes:$minutes)). ":" .($seconds == 0?"00":($seconds <10?"0".$seconds:$seconds));
    }
}

