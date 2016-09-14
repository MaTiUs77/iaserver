<?php

namespace IAServer\Http\Controllers\MonitorPedidos\Model;

use Illuminate\Database\Eloquent\Model;

class amr_deltamonitor extends Model
{
    protected $connection = 'amr_prod';
    protected $table = 'amr_deltamonitor';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

}
