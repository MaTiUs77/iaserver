<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class role_user extends Model
{
    protected $connection = 'mysql';
    protected $table = 'role_user';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');

}
