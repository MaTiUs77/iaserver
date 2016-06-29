<?php

namespace IAServer\Http\Controllers\Memorias\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'planprod.plan_t';

    public function getLineas($query)
    {
        return $this->select(DB::raw('MAX(prioridad) as prioridad, programa, linea'))
            ->groupBy("programa")
            ->groupBy("linea")
            ->orderBy('linea','asc')
            ->get();
    }

    public static function isUpdating()
    {
        $estado = DB::table('planprod.status')->select('estado')->first();
        if($estado->estado==0) {
            return false;
        } else
        { return true; }
    }



/*    public function panel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Panel', 'id', 'id_panel');
    }*/
}
