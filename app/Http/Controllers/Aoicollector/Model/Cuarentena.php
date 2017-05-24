<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class Cuarentena extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.cuarentena';

    public function joinUser()
    {
        return $this->hasOne('IAServer\User', 'id', 'id_user_calidad');
    }

    public function joinDetail()
    {
        return $this->hasMany('IAServer\Http\Controllers\Aoicollector\Model\CuarentenaDetalle', 'id_cuarentena', 'id');
    }

    public function countTotal() {
        return $this->joinDetail()->count();
    }

    public function countCuarentena() {
        return $this->joinDetail()
            ->where('released_at',null)
            ->count();
    }

    public function countReleased() {
        return $this->joinDetail()
            ->where('released_at','<>',null)
            ->count();
    }
}
