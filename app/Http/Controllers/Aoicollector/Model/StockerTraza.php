<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class StockerTraza extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.stocker_traza';

    public function joinRoute()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\StockerRoute', 'id', 'id_stocker_route');
    }
}
