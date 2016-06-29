<?php

namespace IAServer\Http\Controllers\Aoicollector\Model\Backup;

use Illuminate\Database\Eloquent\Model;

class BackupBloque extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata_13_14.inspeccion_bloque';

    public static function buscar($barcode)
    {
        return self::where('barcode', $barcode)
            ->orderBy('id_panel', 'desc')
            ->get();
    }

    public function panel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Backup\BackupPanel', 'id', 'id_panel');
    }
}
