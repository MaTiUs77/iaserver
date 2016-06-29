<?php

namespace IAServer\Http\Controllers\Ipc\Model;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'ipc.categoria';
    public $timestamps = false;
}
