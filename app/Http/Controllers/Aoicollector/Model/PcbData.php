<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class PcbData extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.pcb_data';
}