<?php

namespace IAServer\Http\Controllers\Reparacion\Model;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'reparacion.sector';

    public $timestamps = false;
}
