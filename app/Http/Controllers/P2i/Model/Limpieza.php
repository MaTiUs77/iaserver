<?php

namespace IAServer\Http\Controllers\P2i\Model;

use Illuminate\Database\Eloquent\Model;

class Limpieza extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'p2i.limpieza';
    public $timestamps = false;
}
