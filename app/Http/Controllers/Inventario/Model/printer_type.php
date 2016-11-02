<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class printer_type extends Model
{
    protected $connection = 'inventario';
    protected $table = 'printer_type';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

}
