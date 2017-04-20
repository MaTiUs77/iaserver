<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransaccionWip extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.transaccion_wip';

    public function scopeCountOk($query)
    {
        return $this
            ->where('trans_code',1)
            ->count();
    }

    public function joinRoute()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\StockerRoute', 'id', 'id_last_route');
    }
}
