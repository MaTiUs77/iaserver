<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class RouteOp extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.route_op';
}
