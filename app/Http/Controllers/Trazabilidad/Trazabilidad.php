<?php
namespace IAServer\Http\Controllers\Trazabilidad;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Panel;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\TrazaStocker;
use IAServer\Http\Controllers\Controldeplacas\DatosController;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
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

        return Response::multiple($output,'trazabilidad.index');
    }

    public function wipInfoCtrl($op="")
    {
        $objwip = new Wip();
        $controldeplacas = null;

        if(!empty($op))
        {

            /*$enIa = DB::connection('iaserver')->select(DB::raw("
        select
            hib.*
            from aoidata.inspeccion_panel ip
            left join aoidata.history_inspeccion_bloque hib on hib.id_panel_history = ip.last_history_inspeccion_panel
            where
            ip.inspected_op = '$op'
            "));
            $enWip = DB::connection('traza')->select(DB::raw("
        select REFERENCIA_1 as barcode from XXE_WIP_ITF_SERIE
        where NRO_OP = '$op'"));*/

            $enIa = [];
            $enWip = [];

            $wip = $objwip->findOp($op,true,true);
            $smt = SMTDatabase::findOp($op);
            SMTDatabase::syncSmtWithWip($smt,$wip);

            if(isset($smt->modelo)) {
                $smt->registros = Panel::where('inspected_op',$op)->count();
                $controldeplacas = (object) DatosController::salidaByOp($op);
            }

            $sinDeclarar = Panel::sinDeclarar($op);

//            $wipPeriod = collect($wip->period($op)->get());

            $manualWip = new WipSerie();
            $manualWiph = new WipSerieHistory();

            $manualWipSerie = $manualWip->transactionResume($op,true);
            $manualWipHistory = $manualWiph->transactionResume($op,true);
        }

        $output = compact('op','wip','smt','controldeplacas','manualWipSerie','manualWipHistory','enIa','enWip','sinDeclarar');

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
        $referencia = Input::get('barcode');

        $wip = new WipSerie();
        $result = $wip->declarar('UP3',$wipInfo->wip_ot->nro_op,$wipInfo->wip_ot->codigo_producto,$qty,$referencia);

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

    public function formAllProdStocker($op)
    {
        $tstocker = new TrazaStocker();
        $allstocker = $tstocker->withOp($op);

        $output = compact('allstocker');
        return view('trazabilidad.partial.allstocker',$output);
    }

    public function formTransOk($op,$modo, $trans_ok=null,$manual=false,$ebs_error_trans=null)
    {
        $serie_table = array();
        $history_table = array();

        $serie_trans = null;
        $history_trans = null;

        if($manual=="db") { $manual=false; }

        switch($modo)
        {
            case 'serie':
                $serie = new WipSerie();
                $serie_trans = $serie->wipInfoTransOk($op,$trans_ok,50,$manual,$ebs_error_trans);
                $serie_table = Util::eloquentToTable($serie_trans,true);

                break;
            case 'history':
                $history = new WipSerieHistory();
                $history_trans = $history->wipInfoTransOk($op,$trans_ok,50,$manual,$ebs_error_trans);
                $history_table = Util::eloquentToTable($history_trans,true);

            break;
        }

        $output = compact('modo','serie_table','history_table','serie_trans','history_trans');

        return view('trazabilidad.detalle',$output);
    }

    public function transportOp()
    {
        $regex = '/([0-9]+)/';

        $input = Input::get('barcodes');
        $newop = Input::get('newop');

        $newsmt = OrdenTrabajo::where('op',$newop)->first();

        preg_match_all($regex, $input, $matches);

        $inspecciones = [];
        foreach ($matches[0] as $barcode) {
            $panel = Panel::where('panel_barcode', $barcode)->first();

            if(Input::get('execute') =='execute' )
            {
              /*  $oldsmt = OrdenTrabajo::where('op',$panel->inspected_op)->first();

                $oldsmt->prod_aoi = $oldsmt->prod_aoi - $panel->bloques;
                $oldsmt->save();

                $newsmt->prod_aoi = $newsmt->prod_aoi + $panel->bloques;
                $newsmt->save();*/

                $panel->inspected_op = $newsmt->op;
                $panel->save();

                $history = PanelHistory::where('panel_barcode', $panel->panel_barcode)->get();
                foreach ($history as $panelHistory) {
                    $panelHistory->inspected_op = $panel->inspected_op;
                    $panelHistory->save();
                }
            }

            $inspecciones[] = $panel;
        }

        $output = compact('inspecciones','newop');

        return Response::multiple($output,'trazabilidad.transportop.index');
    }
}
