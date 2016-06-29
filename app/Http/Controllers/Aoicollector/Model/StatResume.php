<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class StatResume extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.stat_resume';

    public $timestamps = false;
}
