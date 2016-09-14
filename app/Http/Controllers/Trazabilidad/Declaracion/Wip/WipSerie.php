<?php
namespace IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip;

use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\XXEWipITFSerie;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\DB;

class WipSerie extends WipSerieCommons
{
    public $class = 'IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\XXEWipITFSerie';

    /**
     * Localiza un barcode en XXEWipITFSerie se puede filtrar por OP y por TRANS_OK
     *
     * @param string $barcode
     * @param string $op
     * @param int $transOk
     * @return mixed
     */
    public function findBarcode($barcode,$op="",$transOk="")
    {
        $serie = XXEWipITFSerie::select([
                'id',
                'nro_op',
                'nro_informe',
                'codigo_producto',
                'cantidad',
                'referencia_1',
                'fecha_proceso',
                'trans_ok',
                'ebs_error_desc',
                'ebs_error_trans',
                'fecha_insercion']
            )
            ->where('organization_code','UP3')
            ->where('referencia_1',$barcode);

        if(!empty($op))
        {
            $serie->where('nro_op',$op);
        }

        if(is_numeric($transOk))
        {
            $serie->where('trans_ok',$transOk);
        }

        $result = $serie->orderBy('fecha_insercion','desc')->get();

        //$serie = array_change_key_case($serie,CASE_LOWER);
        return $result;
    }

    public function findBarcodeSecundario($barcode,$op="",$transOk="")
    {
        $serie = XXEWipITFSerie::select([
                'id',
                'nro_op',
                'nro_informe',
                'codigo_producto',
                'cantidad',
                'referencia_1',
                'fecha_proceso',
                'trans_ok',
                'ebs_error_desc',
                'ebs_error_trans',
                'fecha_insercion']
        )
            ->where('referencia_1','like',$barcode.'-%');

        if(!empty($op))
        {
            $serie->where('nro_op',$op);
        }

        if(is_numeric($transOk))
        {
            $serie->where('trans_ok',$transOk);
        }

        $serie = $serie->where('organization_code','UP3')
            ->orderBy('fecha_insercion','desc');

        $result = $serie->get();

        return $result;
    }


    /**
     * Declara un semielaborado en XXEWipITFSerie
     *
     * @param string $organization UP3
     * @param string $op OP-12345
     * @param string $semielaborado 4-651-MEM4356231
     * @param int $cantidad 1
     * @param string $referencia Puede ser el barcode de un panel
     * @return XXEWipITFSerie
     */
    public function declarar($organization,$op,$semielaborado,$cantidad,$referencia="")
    {
        $serie = new XXEWipITFSerie();
        $serie->ORGANIZATION_CODE = $organization;
        $serie->NRO_OP = $op;
        $serie->CODIGO_PRODUCTO = $semielaborado;
        $serie->CANTIDAD = $cantidad;
        $serie->TRANS_OK = 0;

        if(!empty($referencia))
        {
            $serie->REFERENCIA_1 = $referencia;
        }

        $serie->save();

        return $serie;
    }

    /**
     * Obtiene un objeto de XXEWipITFSerie segun su ID
     *
     * @param int $id_traza
     * @return mixed
     */
    public function findByIdTraza($id_traza)
    {
        $serie = XXEWipITFSerie::select([
                'id',
                'nro_op',
                'nro_informe',
                'codigo_producto',
                'cantidad',
                'referencia_1',
                'fecha_proceso',
                'trans_ok',
                'ebs_error_desc',
                'ebs_error_trans',
                'fecha_insercion']
            )
            ->where('id',$id_traza)
            ->where('organization_code','UP3')
            ->orderBy('fecha_insercion','desc')
            ->first();

        return $serie;
    }

    public function period($op, $minutes = 60)
    {
        $sql = XXEWipITFSerie::select(
            DB::raw("
                SUM(\"CANTIDAD\") as total,
                nro_op as op,
                DATEPART(YEAR, FECHA_INSERCION) as anio,
                DATEPART(MONTH, FECHA_INSERCION) as mes,
                DATEPART(DAY, FECHA_INSERCION) as dia,
                DATEPART(HOUR, FECHA_INSERCION) as periodo,
                (DATEPART(MINUTE, FECHA_INSERCION) / ".$minutes.") as minuto
            ")
        )
            ->where('nro_op',$op)
            ->groupBy(DB::raw("
                nro_op,
                DATEPART(YEAR, FECHA_INSERCION),
                DATEPART(MONTH, FECHA_INSERCION),
                DATEPART(DAY, FECHA_INSERCION),
                DATEPART(HOUR, FECHA_INSERCION),
                (DATEPART(MINUTE, FECHA_INSERCION) / ".$minutes.")
            "))
            ->orderBy(DB::raw("
                DATEPART(MONTH, FECHA_INSERCION) asc,
                DATEPART(DAY, FECHA_INSERCION) asc,
                DATEPART(HOUR, FECHA_INSERCION)
            "));

        return $sql;
    }
}
