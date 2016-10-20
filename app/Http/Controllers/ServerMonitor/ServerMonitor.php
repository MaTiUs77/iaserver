<?php

namespace IAServer\Http\Controllers\ServerMonitor;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Reparacion\Model\Historial;
use IAServer\Http\Controllers\ServerMonitor\Model\ServerMonitorModel;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ServerMonitor extends Controller
{
    public function index() {
        return view('servermonitor.index');
    }

    public function lista() {
        return ServerMonitorModel::all();
    }
}
