<?php

namespace IAServer\Http\Controllers\ServerMonitor\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;

class ServerMonitorModel extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'servermonitor.server';
}
