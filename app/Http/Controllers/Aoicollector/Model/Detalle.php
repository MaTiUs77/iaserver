<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.inspeccion_detalle';

    public function joinFaultcode()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Faultcode', 'faultcode', 'faultcode');
    }

    /**
     * Obtiene la descripcion del faultcode, en caso de que no exista en la tabla, se devuelve Descripcion desconocida
     *
     * @return mixed|string
     */
    public function scopeDescripcion() {
        $f = $this->joinFaultcode;
        return ($f == null) ? 'Descripcion desconocida' : $f->descripcion;
    }
}
