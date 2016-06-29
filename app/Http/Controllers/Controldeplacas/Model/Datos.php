<?php

namespace IAServer\Http\Controllers\Controldeplacas\Model;

use IAServer\Http\Controllers\IAServer\Debug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Datos extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'placas_dev.datos';

    public $timestamps = false;

    public static function salidas($id_sector,$fecha=null)
    {
        $datos = Datos::where('id_sector',$id_sector);

        if($fecha)
        {
            $datos = $datos->where('fecha',$fecha);
        } else
        {
            $datos = $datos->whereRaw('fecha = CURDATE()');
        }

        $datos = $datos->orderBy('hora','desc')->get();

        return $datos;
    }

    public function sector()
    {
        return $this->hasOne('IAServer\Http\Controllers\Controldeplacas\Model\Sector', 'id', 'id_sector');
    }

    public function turno()
    {
        return $this->hasOne('IAServer\Http\Controllers\Controldeplacas\Model\Turno', 'id', 'id_turno');
    }

    public function destino()
    {
        return $this->hasOne('IAServer\Http\Controllers\Controldeplacas\Model\Sector', 'id', 'id_destino');
    }

    public function smt()
    {
        return $this->hasOne('IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo', 'op', 'op');
    }

    public function countSalidas($op)
    {
        $count = Datos::where('op',$op)->sum('cantidad');
        return $count;
    }
}
