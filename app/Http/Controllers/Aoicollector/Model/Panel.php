<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Panel extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.inspeccion_panel';

    public function scopeBuscarPanel($query, $barcode)
    {
        return $this->where('panel_barcode',$barcode)
            ->leftJoin('aoidata.maquina', 'maquina.id','=','id_maquina')
            ->select(['inspeccion_panel.*','maquina.linea'])
            ->get();
    }
}
