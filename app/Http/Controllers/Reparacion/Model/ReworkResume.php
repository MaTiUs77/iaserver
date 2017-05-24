<?php

namespace IAServer\Http\Controllers\Reparacion\Model;

use Illuminate\Database\Eloquent\Model;

class ReworkResume extends Model
{
    protected $connection = 'reparacion';
    protected $table = 'rework_resume';

    public $timestamps = false;
}
