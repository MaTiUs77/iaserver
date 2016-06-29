<?php

namespace IAServer\Http\Controllers\P2i\Model;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'p2i.modelo';

    public $timestamps = false;
}
