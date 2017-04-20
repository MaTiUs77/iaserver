<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use Illuminate\Database\Eloquent\Model;

class BloqueHistory extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.history_inspeccion_bloque';
    public $timestamps = false;

    public static function buscar($barcode)
    {
        $q = self::where('barcode', $barcode)
            ->where('etiqueta','E')
            ->orderBy('id_panel_history', 'desc');

        return $q->get();
    }

    public function panel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\PanelHistory', 'id_panel_history', 'id_panel_history');
    }

    public function wip($op)
    {
        $verify = new VerificarDeclaracion();
        return $verify->wip($this->barcode,$op);
    }

    public function twip()
    {
        $verify = new VerificarDeclaracion();
        return $verify->twip($this->barcode);
    }
}
