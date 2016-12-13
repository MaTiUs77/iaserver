<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class printer_config extends Model
{
    protected $connection = 'inventario';
    protected $table = 'printer_config';
    protected $primaryKey = 'id_printer_config';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

}
