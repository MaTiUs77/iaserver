<?php

namespace IAServer\Http\Controllers\ServerMonitor;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class ServerMonitor extends Controller
{
    public function index() {
        return view('servermonitor.index');
    }

    public function redis() {
        try
        {
            $monitor = \LRedis::smembers('servermonitor::online');

            $status = array();
            foreach ($monitor as $item) {
                $status[] = json_decode(\LRedis::get('servermonitor::status::'.$item));
            }

            $output = compact('monitor','status');

            return $output;
        }
        catch(\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
