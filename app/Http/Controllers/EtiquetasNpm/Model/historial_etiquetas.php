<?php

namespace IAServer\Http\Controllers\EtiquetasNpm\Model;

use Illuminate\Database\Eloquent\Model;

class historial_etiquetas extends Model
{
    protected $connection = 'labelnpm';
    protected $table = 'historial_etiquetas';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;
}
