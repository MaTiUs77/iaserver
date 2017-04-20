<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class StatResume extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.stat_resume';

    public $timestamps = false;
}
