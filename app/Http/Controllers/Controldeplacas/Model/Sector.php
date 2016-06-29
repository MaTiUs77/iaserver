<?php

namespace IAServer\Http\Controllers\Controldeplacas\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sector extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'placas_dev.sector';

    public $timestamps = false;
}
