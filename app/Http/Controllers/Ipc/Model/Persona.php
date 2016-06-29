<?php

namespace IAServer\Http\Controllers\Ipc\Model;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'ipc.persona';
    public $timestamps = false;

    public function sector()
    {
        return $this->hasOne('IAServer\Http\Controllers\Ipc\Model\Sector','id_sector','id_sector');
    }

    public function categoria()
    {
        return $this->hasOne('IAServer\Http\Controllers\Ipc\Model\Categoria','id_categoria','id_categoria');
    }
}
