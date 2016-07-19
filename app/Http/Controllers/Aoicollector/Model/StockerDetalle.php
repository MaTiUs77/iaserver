<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class StockerDetalle extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.stocker_detalle';

    public $fillable = ['id_stocker','id_panel'];

    public $timestamps = false;

    public function joinPanel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Panel', 'id', 'id_panel');
    }
}
