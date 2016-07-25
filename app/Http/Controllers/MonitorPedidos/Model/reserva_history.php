<?php

namespace IAServer\Http\Controllers\MonitorPedidos\Model;

use Illuminate\Database\Eloquent\Model;

class reserva_history extends Model
{
    protected $connection = 'db2_tools';
    protected $table = 'reserva_history';

    public $timestamps = false;
}
