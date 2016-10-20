<?php

namespace IAServer\Http\Controllers\MonitorPedidos\Model;

use Illuminate\Database\Eloquent\Model;

class pedidos_rechazados extends Model
{
    protected $connection = 'db2_tools';
    protected $table = 'pedidos_rechazados';
    public $timestamps = false;
}
