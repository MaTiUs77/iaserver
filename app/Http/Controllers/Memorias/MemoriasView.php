<?php
namespace IAServer\Http\Controllers\Memorias;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Memorias\Model\Grabacion;
use IAServer\Http\Controllers\Memorias\Model\Plan;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class MemoriasView extends Memorias
{
    public $debug;

    function __construct()
    {
        //$this->debug = new Debug($this);
        $this->middleware('role:admin|memorias_operador',['except' => ['index','reporte']]);
    }

    public function index()
    {
        return $this->printCartelera();
    }

    public function reporte()
    {
        // Crea una session para filtro de fecha
        $defaultFrom = Carbon::now()->format('d-m-Y');
        $defaultTo = Carbon::now()->format('d-m-Y');
        Filter::makeSession('from_session',$defaultFrom);
        Filter::makeSession('to_session',$defaultTo);

        // Obtengo la fecha, y cambio el formato 16-09-2015 -> 2015-09-16
        $fechaFrom = Util::dateToEn(Session::get('from_session'));
        $fechaTo = Util::dateToEn(Session::get('to_session'));

        $produccion = Grabacion::filtroFecha($fechaFrom,$fechaTo)->get();
        $periodoUser =  Grabacion::filtroFecha($fechaFrom,$fechaTo)->select(DB::raw('
                DATE(fecha) as periodo,
                id_usuario,
                SUM(cantidad) as total'))
            ->groupBy(DB::raw('DATE(fecha), id_usuario'))->get();

        $periodoDiario = $periodoUser->groupBy('periodo');

        $output = compact('produccion','periodoUser','periodoDiario');

        return Response::multiple($output,'memorias.reporte');
    }

    public function search($op="")
    {
        if(empty($op))
        {
            $op = Input::get('search');
        }
        return $this->printCartelera($op);
    }

     /*
     * Formulario para declaracion de memorias.
     */
    public function formDeclarar($op)
    {
        $plan = OrdenTrabajo::findMemoryByOp($op);

        if(count($plan))
        {
            $programa = $this->cartelera($plan->modelo,$plan->lote,$op);

            $programa = head(head(head(head($programa))));
            $smt = head(head($programa->memorias->smt));
            $ingenieria =  $programa->memorias->ingenieria;
            if(is_array($ingenieria))
            {
                $ingenieria = (object) collect($programa->memorias->ingenieria)->get(str_replace('MEM-','',$smt->panel));
            } else {
                $ingenieria = $programa->memorias->ingenieria;
            }
        }

        return view('memorias.widget.modal_declare_with_tabs',compact('programa','smt','ingenieria'));
    }

    /*
     * Ejecuta submit para declaracion de memorias
     */
    public function formDeclararSubmit($op,$redir="")
    {
        $op = strtoupper($op);
        $cantidad = Input::get('cantidad');

        $smt = OrdenTrabajo::findMemoryByOp($op);

        $wip = new Wip();
        $smt->wip = $wip->findOp($op);

        $referencia = str_replace('MEM-', '', $smt->panel);

        if ($cantidad > 0)
        {
            if(($cantidad + $wip->wip_ot->quantity_completed) > $smt->wip->wip_ot->start_quantity)
            {
                return back()->with('message','La cantidad '.$cantidad.' ('.$cantidad.' + '.$wip->wip_ot->quantity_completed.') > '.$smt->wip->wip_ot->start_quantity.' a declarar, supera la cantidad restante del lote!, no se efectua la declaracion.');
            }
        } else {
            return back()->with('message','La cantidad '.$cantidad.' no es valida, no se efectua la declaracion.');
        }

        $grabacion = new Grabacion();
        $grabacion->fecha = Carbon::now();
        $grabacion->color = Input::get('color');
        $grabacion->semielaborado = $smt->wip->wip_ot->codigo_producto;
        $grabacion->id_programador = Input::get('id_programador');
        $grabacion->op = $op;
        $grabacion->cantidad = $cantidad;
        $grabacion->id_usuario= Auth::user()->id;
        $grabacion->referencia = $referencia;

        $declararWip = $this->declararWip($op,$grabacion->semielaborado,$cantidad);

        if(isset($declararWip->id))
        {
            $grabacion->id_traza = $declararWip->id;
        }

        $grabacion->save();

        $this->zebraPrint($op,$cantidad);

        if(empty($redir))
        {
            return redirect(route('memorias.search',$op))->with('message','Declaracion realizada!');;
        } else
        {
            return redirect(route($redir,$op))->with('message','Declaracion realizada!');
        }
    }

    public function printCartelera($filtrar='')
    {
        $mode = null;

        if(Plan::isUpdating())
        {
            return view('errors.default',
            [
                'titulo'=>'ATENCION',
                'mensaje'=>'El sistema esta actualizando el plan de produccion, ingrese mas tarde...',
                'reload' => 2
            ]);

        }

        if(starts_with(strtoupper($filtrar),'OP-'))
        {
            $mode = 'op';
        }

        switch($mode)
        {
            case 'op':
                $plan = OrdenTrabajo::findMemoryByOp($filtrar);

                if(isset($plan->modelo))
                {
                    $programa = $this->cartelera($plan->modelo,$plan->lote,$filtrar);
                }
                break;
            default:
                $programa = $this->cartelera($filtrar);
                break;
        }

        return view('memorias.cartelera',compact('programa'));
    }
}
