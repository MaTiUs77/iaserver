<?php

namespace IAServer\Http\Controllers\MonitorPedidos\Model;

use Illuminate\Database\Eloquent\Model;

class ins_result extends Model
{
    protected $connection = 'db2_tools';
    protected $table = 'ins_result';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;
}
