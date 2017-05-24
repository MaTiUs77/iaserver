<?php

namespace IAServer\Http\Controllers\ControlDeStencil\Model;

use Illuminate\Database\Eloquent\Model;

class Placas extends Model
{
    protected $connection = "stencil";
    protected $table = "placas";
    public $timestamps = false;
}
