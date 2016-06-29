<?php

namespace IAServer\Http\Controllers\Ipc\Model;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'ipc.sector';
    public $timestamps = false;
}
