<?php

namespace IAServer\Http\Controllers\Reparacion\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reparacion extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'reparacion.reparacion';
}
