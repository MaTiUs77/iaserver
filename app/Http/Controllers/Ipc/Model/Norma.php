<?php

namespace IAServer\Http\Controllers\Ipc\Model;

use Illuminate\Database\Eloquent\Model;

class Norma extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'ipc.norma';
    public $timestamps = false;
}
