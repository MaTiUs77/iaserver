<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

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

    public function twip()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip', 'barcode', 'barcode');
    }

    public function wip($op)
    {
        $w = new Wip();
        $wip = $w->findBarcode($this->barcode, $op);

        $declarado = false;
        $pendiente = false;

        if(count($wip)>0)
        {

            if($wip->where('trans_ok',"1")->count()>0)
            {
                $declarado = true;
            }

            if($wip->where('trans_ok',"0")->count()>0)
            {
                $pendiente = true;
            }
        }

        $output = array();
        $output['declarado'] = $declarado;
        $output['pendiente'] = $pendiente;
        $output['last'] = $wip->first();
        $output['historial'] = $wip;

        return (object) $output;
    }
}
