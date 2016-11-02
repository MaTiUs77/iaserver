<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class planta extends Model
{
    protected $connection = 'inventario';
    protected $table = 'planta';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

}
