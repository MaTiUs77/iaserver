<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

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

    public function twip()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip', 'barcode', 'barcode');
    }
}
