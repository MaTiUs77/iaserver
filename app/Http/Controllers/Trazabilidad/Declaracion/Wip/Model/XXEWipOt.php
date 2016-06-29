<?php
namespace IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class XXEWipOt extends Model
{
    protected $connection = 'traza';
    protected $table = 'XXE_WIP_OT';

    public $timestamps = false;

    public static function getOtByDescription($description)
    {
        $item = self::otQuery()
            ->whereRaw("OT.DESCRIPTION like '%" . $description . "%' ")
            ->get();

        return $item;
    }
    public static function getOtInsaut()
    {
        $item = self::otQuery()
            ->whereRaw("OT.SEGMENT1 like '4-651%'")
            ->get();

        return $item;
    }

    /**
     * Obtiene datos de OP abierta en XXEWipOt
     *
     * @param string $op OP-12345
     * @return XXEWipOt
     */
    public static function findOp($op)
    {
        $item = self::otQuery()
            ->whereRaw("ot.wip_entity_name = '$op'")
            ->first();

        if($item!=null)
        {
            $item->porcentaje = 0;
            $item->restante = $item->start_quantity - $item->quantity_completed;
            if($item->quantity_completed>0)
            {
                $item->porcentaje = (float) number_format((($item->quantity_completed / $item->start_quantity) * 100), 1, '.', '');
            }
        }
        return $item;
    }

    private static function otQuery()
    {
        $item = XXEWipOt::select(
            DB::raw("
            (
                SELECT TOP(1) S.FECHA_INSERCION FROM XXE_WIP_ITF_SERIE S
                WHERE
                    S.NRO_OP = ot.wip_entity_name AND
                    S.ORGANIZATION_CODE = 'UP3'
                ORDER BY
                    S.FECHA_INSERCION DESC
            ) as ultimo_serie,
            (
                SELECT TOP(1) S.FECHA_INSERCION FROM XXE_WIP_ITF_SERIE_History S
                WHERE
                    S.NRO_OP = ot.wip_entity_name AND
                    S.ORGANIZATION_CODE = 'UP3'
                ORDER BY
                    S.FECHA_INSERCION DESC
            ) as ultimo_history,
            ot.organization_code,
            ot.wip_entity_name as nro_op,
            ot.start_quantity,
            ot.quantity_completed,
            ot.alternate_bom_designator,
            ot.primary_item_id,
            ot.segment1 as codigo_producto,
            ot.description"
            ))
            ->from('XXE_WIP_OT AS ot')
            ->whereRaw("ot.organization_code = 'UP3'");

        return $item;
    }
}
