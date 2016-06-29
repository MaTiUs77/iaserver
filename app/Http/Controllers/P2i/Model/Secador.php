<?php

namespace IAServer\Http\Controllers\P2i\Model;

use Illuminate\Database\Eloquent\Model;

class Secador extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'p2i.secador';
    public $timestamps = false;

    public function modelo()
    {
        return $this->hasOne('IAServer\Http\Controllers\P2i\Model\Modelo','id','id_modelo');
    }

    public function operador()
    {
        return $this->hasOne('IAServer\User','id','id_operador');
    }
}
