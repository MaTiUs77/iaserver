<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use Illuminate\Database\Eloquent\Model;

class BloqueHistory extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.history_inspeccion_bloque';

    public static function buscar($barcode)
    {
        return self::where('barcode', $barcode)
            ->orderBy('id_panel_history', 'desc')
            ->get();
    }

    public function panel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\PanelHistory', 'id_panel_history', 'id_panel_history');
    }

    /*
    public function jTransaccionWip()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip', 'barcode', 'barcode');
    }
*/

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
