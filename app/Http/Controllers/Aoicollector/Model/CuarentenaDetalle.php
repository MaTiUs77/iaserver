<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class CuarentenaDetalle extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.cuarentena_detalle';

    public function joinCuarentena()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Cuarentena', 'id', 'id_cuarentena');
    }
}
