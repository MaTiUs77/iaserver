<?php

namespace IAServer\Http\Controllers\Memorias\Model;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'memorias.observacion';
}
