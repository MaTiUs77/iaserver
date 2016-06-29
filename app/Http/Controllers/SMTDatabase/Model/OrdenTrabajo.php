<?php

namespace IAServer\Http\Controllers\SMTDatabase\Model;

use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'smtdatabase.orden_trabajo';

    /**
     * Localiza todos los paneles de la OP que contengan MEM-%
     *
     * @param $query empty
     * @param string $op
     * @return Model
     */
    public static function findMemoryByOp($op)
    {
        return self::where('panel','like','MEM-%')
            ->where('OP',$op)
            ->first();
    }

    public static function findPanelByModeloLote($modelo,$lote,$panel)
    {
        return self::where('modelo',$modelo)
            ->where('lote',$lote)
            ->where('panel',$panel)
            ->first();
    }

    public static function listPanelsByModeloLote($modelo,$lote)
    {
        return self::where('modelo',$modelo)
            ->where('lote',$lote)
            ->get();
    }
}
