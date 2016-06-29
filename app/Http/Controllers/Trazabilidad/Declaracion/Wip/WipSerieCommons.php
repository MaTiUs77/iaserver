<?php
namespace IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip;

use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\TransOkDet;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WipSerieCommons extends Controller
{
    public $class = null;

    public function wipOtInfoFromTransactions($op)
    {
        $instance = new $this->class;

        $query = $instance::select([
            "nro_op",
            "organization_code",
            "codigo_producto"
        ])
            ->where('nro_op',$op)
            ->where('ORGANIZATION_CODE','UP3')
            ->orderBy('fecha_insercion','desc')
            ->limit(1)
            ->first();

        return $query;
    }

    public function wipInfoTransOk($op, $trans_ok=null, $paginate=0)
    {
        $instance = new $this->class;
        $query = $instance::where('nro_op',$op);

        if($trans_ok!=null)
        {
            $query = $query->where("trans_ok",$trans_ok);
        }

        $query = $query->orderBy('fecha_insercion','desc');

        if($paginate>0)
        {
            $query = $query->paginate(50);
        } else
        {
            $query = $query->get();
        }

        return $query;
    }

    public function transactionResume($op, $onlyManualDeclaration=false)
    {
        $instance = new $this->class;
        $query = $instance::select(DB::raw('
            SUM(w.cantidad) as total,
            w.trans_ok,
            d.description,
            w.ebs_error_trans
        '))->from($instance->getTable().' as w')
            ->leftJoin('trans_ok_det as d',DB::raw( 'd.id' ), '=', DB::raw( '[w].trans_ok' ) )
            ->whereRaw("w.nro_op = '$op' ");

        if($onlyManualDeclaration)
        {
            $query = $query->where('w.cantidad','>',1);
        }

        $query = $query->groupBy('w.trans_ok')
            ->groupBy('d.description')
            ->groupBy('w.ebs_error_trans')
            ->get();

        return $query;

/*
        $query = "
          SELECT
            SUM(w.cantidad) as total,
            w.trans_ok,
            d.description,
            w.ebs_error_trans
          FROM
            Traza_material.dbo.".$this->table." w
            LEFT OUTER JOIN Traza_material.dbo.trans_ok_det d on d.id = w.trans_ok

          WHERE
            w.nro_op = '".$op."'
        ";

        if($onlyManualDeclaration)
        {
            $query = $query. " and w.cantidad > 1";
        }

        $query = $query ."
          GROUP BY
            w.trans_ok, d.description, w.ebs_error_trans
        ";

        $sql = DB::connection('traza')->select($query);

        return $sql;*/
    }
}
