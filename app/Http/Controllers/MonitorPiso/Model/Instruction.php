<?php

namespace IAServer\Http\Controllers\MonitorPiso\Model;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $connection = "db2_tools";
    protected $table = "instruction";
}