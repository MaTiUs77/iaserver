<?php

namespace IAServer\Http\Controllers\Controldeplacas\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Turno extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'placas_dev.turno';

    public $timestamps = false;
}
