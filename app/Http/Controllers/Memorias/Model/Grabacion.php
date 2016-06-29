<?php

namespace IAServer\Http\Controllers\Memorias\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Grabacion extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'memorias.grabacion';

    public $timestamps = false;

    public function operador()
    {
        return $this->hasOne('IAServer\User','id','id_usuario');
    }

    public function scopeTransPendiente()
    {
        return $this->where('traza_code','0')->get();
    }

    public static function filtroFecha($fechaFrom,$fechaTo)
    {
        return self::whereRaw(DB::raw(" DATE(fecha) between '".$fechaFrom."' and '".$fechaTo."'"));
    }
}
