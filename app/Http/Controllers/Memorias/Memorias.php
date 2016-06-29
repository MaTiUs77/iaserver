<?php
namespace IAServer\Http\Controllers\Memorias;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Controllers\Ingenieria\Ingenieria;
use IAServer\Http\Controllers\Memorias\Model\Grabacion;
use IAServer\Http\Controllers\Memorias\Model\Plan;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\WipSerie;
use IAServer\Http\Controllers\Zebra\Zebra;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class Memorias extends Controller
{
    //$host = '10.30.56.116'; // Ultima Zebra en Memorias
    //$host = '10.30.30.128'; // Zebra de Diego

    public $zebra_ip = '10.30.30.115';
    public $zebra_port = 9100;
    public $zebra_prn = 'zebra/memorias';
    public $zebra_ebs = 'P3-UshZebra5';

    public static function updateTransPendientes()
    {
        $pendientes = Grabacion::transPendiente();

        foreach($pendientes as $pendiente)
        {
            $serie = new WipSerie();
            $estado = $serie->findByIdTraza($pendiente->id_traza);

            if(isset($estado->trans_ok))
            {
                $pendiente->traza_code = $estado->trans_ok;
                $pendiente->traza_det = $estado->trans_ok_det->description;
                $pendiente->save();
            } else
            {
                $pendiente->traza_code = 500;
                $pendiente->traza_det = "ID De traza no encontrado";
                $pendiente->save();
            }
        }
    }

    public function declararWip($op,$semielaborado,$cantidad)
    {
        $wip = new WipSerie();
        $result = $wip->declarar('UP3',$op, $semielaborado ,$cantidad);

        $output = new \stdClass();
        if($result)
        {
            if(isset($result->id))
            {
                $output->id = $result->id;
            } else
            {
                $output->error  = 'Declaracion ejecutada: Error al obtener ID de traza';
            }
        }

        return $output;
    }

    public function zebraPrint($op="",$cantidad="")
    {
        $smt = OrdenTrabajo::findMemoryByOp($op);

        $wip = new Wip();
        $smt->wip = $wip->findOp($op);

        $params = [
            $op,
            $smt->modelo,
            str_replace('MEM-','',$smt->panel),
            $smt->lote,
            $cantidad,
            $smt->wip->wip_ot->codigo_producto,
            'DV',
            '1',
            $this->zebra_ebs
        ];

        $label = new Zebra($this->zebra_ip, $this->zebra_port, $this->zebra_prn);
        $label->template($params);
        $label->imprimir();

        if(empty($label->error)) {
            $status = array(
                "impresion"=>true,
            );
        } else {
            $status = array(
                "impresion"=>false,
                "error"=>$label->error
            );
        }

        return $status;
    }

    public function cartelera($filtrar_modelo="",$filtrar_lote="",$filtrar_op="") {
        $plan = Plan::whereRaw('fecha between ? and ?',
            [
                Carbon::now()->subWeek(),
                Carbon::now()->addWeek(2)
            ]
        )
            ->groupBy(['modelo','lote','cantidad','programa','linea'])
            ->orderBy('fecha','asc');

        if(!empty($filtrar_modelo))
        {
            $plan->where('modelo', 'like', $filtrar_modelo . '%');
        }

        if(!empty($filtrar_lote))
        {
            $plan->where('lote',$filtrar_lote);
        }

        $plan = $plan->get();

        $output = array();
        foreach($plan as $pitem)
        {
            $orden = OrdenTrabajo::where('modelo',$pitem->modelo)
                ->where('lote',$pitem->lote)
                ->where('panel','like','MEM-%');

            if(!empty($filtrar_op))
            {
                $orden->where('OP',$filtrar_op);
            }
            $orden = $orden->get();

            $obj = new \stdClass();
            $obj->plan = $pitem;

            foreach($orden as $oitem)
            {
                $wip = new Wip();
                $oitem->wip = $wip->findOp($oitem->op);
            }

            $ingenieria = new Ingenieria();
            $memorias_ingenieria =  $ingenieria->getPositions($pitem->modelo,$pitem->lote);

            $meminfo = new \stdClass();

            if($ingenieria->error || count($memorias_ingenieria)==0)
            {
                // No existe lista de ingenieria
                $meminfo->ingenieria = $ingenieria->error;
            } else {

                $meminfo->ingenieria = $memorias_ingenieria;
            }

            $meminfo->smt = $orden;
            $obj->memorias = $meminfo;

            $output[] = $obj;
        }

        $programa = collect($output)->sortBy('plan.programa')->groupBy('plan.programa');

        return $programa;
    }
}
