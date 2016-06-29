<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.maquina';
    
    public function scopeRns($query) {
        return $query->where('tipo','R')->get();
    }

    public function scopeVtwin($query) {
        return $query->where('tipo','W')->get();
    }

    public function scopeVts($query) {
        return $query->where('tipo','V')->get();
    }
}
