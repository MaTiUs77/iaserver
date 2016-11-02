<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    protected $connection = 'inventario';
    protected $table = 'users';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

}
