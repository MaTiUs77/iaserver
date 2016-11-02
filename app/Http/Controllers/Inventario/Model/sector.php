<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class sector extends Model
{
    protected $connection = 'inventario';
    protected $table = 'sector';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

}
