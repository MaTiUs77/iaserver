<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class StockerRoute extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.stocker_route';
}
