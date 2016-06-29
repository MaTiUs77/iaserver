<?php

namespace IAServer\Http\Controllers\P2i\Model;

use Illuminate\Database\Eloquent\Model;

class Carga extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'p2i.carga';
    public $timestamps = false;

    public function operador()
    {
        return $this->hasOne('IAServer\User','id','id_operador');
    }

}
