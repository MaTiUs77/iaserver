<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use IAServer\User;
use Illuminate\Database\Eloquent\Model;

class StockerTraza extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.stocker_traza';

    public function joinRoute()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\StockerRoute', 'id', 'id_stocker_route');
    }
}
