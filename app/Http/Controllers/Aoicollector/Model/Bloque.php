<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class Bloque extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.inspeccion_bloque';

    public function panel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Panel', 'id', 'id_panel');
    }
}
