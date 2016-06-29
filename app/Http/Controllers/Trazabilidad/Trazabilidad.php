<?php
namespace IAServer\Http\Controllers\Trazabilidad;

use IAServer\Http\Controllers\Aoicollector\Model\Panel;
use IAServer\Http\Controllers\Controldeplacas\DatosController;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\WipSerie;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\WipSerieHistory;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Trazabilidad extends Controller
{
    public $debug;

    function __construct()
    {
        //$this->debug = new Debug($this,true);
    }

    /**
     * Muestra todas las OP activas o liberadas
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->wipInfo();
    }

    /**
     * Busca una OP
     *
     * @param $op
     * @return \Illuminate\View\View
     */
    public function findOp($op="")
    {
		$op = strtoupper( $op );
		if(empty($op))
		{
			$op = strtoupper( Input::get('op') );
		}	

        return $this->wipInfo($op);
    }

    public function wipInfo($op="")
    {
        $output = $this->wipInfoCtrl($op);

        return Response::multiple_output($output,'trazabilidad.index');
    }

    public function wipInfoCtrl($op="")
    {
        $objwip = new Wip();
        $controldeplacas = null;

        if(!empty($op))
        {
            $wip = $objwip->findOp($op,true,true);
            $smt = SMTDatabase::findOp($op);
            if(isset($smt->modelo)) {
                $smt->registros = Panel::where('inspected_op',$op)->count();
                $controldeplacas = (object) DatosController::salida($smt->modelo,$smt->lote,$smt->panel);
            }

//            $wipPeriod = collect($wip->period($op)->get());

            $manualWip = new WipSerie();
            $manualWiph = new WipSerieHistory();

            $manualWipSerie = $manualWip->transactionResume($op,true);
            $manualWipHistory = $manualWiph->transactionResume($op,true);
        }

        $output = compact('op','wip','smt','controldeplacas','manualWipSerie','manualWipHistory');

        return $output;
   }

    public function formDeclarar($op)
    {
        $wip = new Wip();

        $wipInfo = $wip->findOp($op);
        $smt = SMTDatabase::findOp($op);

        $output = compact('wipInfo','smt');

        return view('trazabilidad.declarar',$output);
    }

    public function formDeclararSend($op)
    {
        $wip = new Wip();
        $wipInfo = $wip->findOp($op);
        $qty = Input::get('cantidad');

        $wip = new WipSerie();
        $result = $wip->declarar('UP3',$wipInfo->wip_ot->nro_op,$wipInfo->wip_ot->codigo_producto,$qty);

        $message = '';
        if($result)
        {
            if(isset($result->id))
            {
                $message = 'Declaracion ejecutada con ID: '.$result->id;
            } else
            {
                $message = 'Declaracion ejecutada: Error al obtener ID de traza';
            }
        }
        return redirect( route('trazabilidad.find.op',$wipInfo->wip_ot->nro_op) )->with('message',$message);
    }

    public function formTransOk($op,$modo, $trans_ok=null)
    {
        $serie_table = array();
        $history_table = array();

        $serie_trans = null;
        $history_trans = null;

        switch($modo)
        {
            case 'serie':
                $serie = new WipSerie();
                $serie_trans = $serie->wipInfoTransOk($op,$trans_ok,50);
                $serie_table = Util::eloquentToTable($serie_trans,true);

                break;
            case 'history':
                $history = new WipSerieHistory();
                $history_trans = $history->wipInfoTransOk($op,$trans_ok,50);
                $history_table = Util::eloquentToTable($history_trans,true);

            break;
        }

        $output = compact('modo','serie_table','history_table','serie_trans','history_trans');

        return view('trazabilidad.detalle',$output);
    }
}
