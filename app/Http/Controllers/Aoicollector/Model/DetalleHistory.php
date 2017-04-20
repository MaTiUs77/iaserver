<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class DetalleHistory extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.history_inspeccion_detalle';

    public function scopeLeftJoinFaultcode($query)
    {
        return $query->leftJoin('aoidata.rns_faultcode','aoidata.rns_faultcode.faultcode','=','aoidata.history_inspeccion_detalle.faultcode');
    }

    public static function fullDetail($id_bloque)
    {
        return self::where('id_bloque_history',$id_bloque)->leftJoinFaultcode()->select(['history_inspeccion_detalle.*','rns_faultcode.descripcion']);
    }

    public function joinFaultcode()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Faultcode', 'faultcode', 'faultcode');
    }

    public function scopeDescripcion() {
        $f = $this->joinFaultcode;
        return ($f == null) ? 'Descripcion desconocida' : $f->descripcion;
    }
}
