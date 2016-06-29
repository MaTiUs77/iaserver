<?php

namespace IAServer\Http\Controllers\MonitorPedidos\Model;

use Illuminate\Database\Eloquent\Model;

class cgs_materialrequest extends Model
{
    protected $connection = 'amr_prod';
    protected $table = 'cgs_materialrequest';

    //protected $fillable = array('op','linMatWip','rawMaterial','codMat','maquina','ubicacion');
    public $timestamps = false;

    public function status()
    {
        return $this->hasOne('Model\cgs_materialrequest');
    }
}
