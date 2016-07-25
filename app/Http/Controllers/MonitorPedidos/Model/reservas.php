<?php

namespace IAServer\Http\Controllers\MonitorPedidos\Model;

use Illuminate\Database\Eloquent\Model;

class reservas extends Model
{
    protected $connection = 'db2_tools';
    protected $table = 'reservas';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

    public function ins_result()
    {
        $this->hasOne('IAServer\Http\Controllers\MonitorPedidos\Model\ins_result','lpn','field1');
    }
}
