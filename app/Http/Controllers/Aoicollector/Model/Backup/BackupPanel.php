<?php

namespace IAServer\Http\Controllers\Aoicollector\Model\Backup;

use Illuminate\Database\Eloquent\Model;

class BackupPanel extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata_13_14.inspeccion_panel';

    public static function buscarPanel($barcode)
    {
        $sql = BackupPanel::where('panel_barcode',$barcode)
            ->leftJoin('aoidata_13_14.maquina', 'maquina.id','=','id_maquina')
            ->select(['inspeccion_panel.*','maquina.linea']);

        return $sql->first();
    }

    public static function buscar($barcode)
    {
        $panel = self::buscarPanel($barcode);
        if(count($panel)>0)
        {
            return $panel;
        } else
        {
            // Luego busca en BloqueHistory
            $bloque = BackupBloque::buscar($barcode);

            if (count($bloque) > 0)
            {
                $panel_barcode = $bloque->first()->panel->panel_barcode;
                return self::buscarPanel($panel_barcode);
            } else
            {
                return null;
            }
        }
    }
}
