<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Lista de inspecciones
 *
 * @package IAServer\Http\Controllers\Aoicollector\Inspection
 * @example code
 * <php>
 *      $list = new InspectionList(new Carbon::now(),new Carbon::now());
 *      $list->setIdMaquina(1);
 *      $list->setMode('MIN');
 *      $list->programsUsedByIdMaquina();
 *      $list->getPaneles();
 * </php>
 */
class InspectionList extends Controller
{
    public $firstApparition = false;

    public $idMaquina = null;

    // Filtro Fecha
    public $desdeCarbon = null;
    public $hastaCarbon = null;

    // Filtro Op
    public $filterOp = null;
    public $filterPeriod = null;


    // Paginacion
    public $porPagina = 50;
    public $paginas = 1;
    public $pagina = 1;
    public $paginate = false;

    // Resultados
    public $filas = 0;
    public $inspecciones = null;
    public $programas = [];

    /*
    *  MAX = Resultados de ultima inspeccion
    *  MIN = Resultados de primer inspeccion
    *
    *  Si una placa es inspeccionada en mas de una AOI,
    *  los resultados de la inspeccion de la placa, en
    *  cada maquina tendra su respectiva primer y ultima
    *  inspeccion
    */
    public $inspectionMinOrMax = 'MAX';

    /**
     * InspectionList constructor.
     *
     * @param Carbon $desde
     * @param Carbon $hasta
     */
    public function __construct(Carbon $desde, Carbon $hasta)
    {
        $this->desdeCarbon = $desde;
        $this->hastaCarbon = $hasta;
    }

    public function setMode($mode)
    {
        switch($mode)
        {
            case 'MINA':
                $this->firstApparition = true;
            case 'MAX':
            case 'MIN':
                $this->inspectionMinOrMax = $mode;
            break;
        }
    }

    public function setIdMaquina($idMaquina)
    {
        $this->idMaquina= $idMaquina;
    }

    public function setPeriod($period)
    {
        if(!empty($period))
        {
            $this->filterPeriod = $period;
        } else
        {
            $this->filterPeriod = null;
        }
    }

    public function setOp($op)
    {
        $this->filterOp = $op;
    }

    public function setPagina($pagina)
    {
        $this->paginate = true;

        if(is_numeric($pagina)) {
            $this->pagina = $pagina;
        } else
        {
            $this->pagina = 1;
        }
    }

    public function find() {
        if ($this->firstApparition) {
            $this->panelFirstGlobalApparition();
        } else {
           $this->panelMachineApparition();
        }
    }

    public function getPaneles() {
        if ($this->firstApparition) {
            $this->panelFirstGlobalApparition();
        } else {
           $this->panelMachineApparition();
        }
    }

    public function getBloques() {
        if ($this->firstApparition) {
            $this->bloqueFirstGlobalApparition();
        } else {
            //bloqueMachineApparition();
            dd('bloqueMachineApparition() no implementado');
        }
    }

    public function programsUsedByIdMaquina()
    {
        if(empty($this->filterOp))
        {
            $sql = PanelHistory::select(DB::raw('programa, id_maquina, inspected_op'))
                ->where('id_maquina',$this->idMaquina);

            if(!empty($turno))
            {
                $sql = $sql->where('turno',$turno);
            }

            $result = $sql->whereRaw("created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
                ->groupBy('programa','inspected_op')
                ->get();

            $this->programas = $result;
        }
    }

    /*
     * Inspecciones creadas en la jornada y en la maquina
     * Si la inspeccion fue realizada el mismo dia en otra maquina esta se visualizaria
     * en su lista
     */
    private function panelMachineApparition()
    {
        $q = PanelHistory::select(DB::raw("
            *,
            (
                select first_history_inspeccion_panel from `aoidata`.`inspeccion_panel` as subp where
                subp.panel_barcode = hp.panel_barcode
            ) as first_history_inspeccion_panel,
            (
                select trans_ok from `aoidata`.`transaccion_wip` as subt where
                subt.barcode = hp.panel_barcode
                order by subt.created_at desc limit 1
            ) as trans_ok,
            (
                select stkr.name from `aoidata`.`transaccion_wip` as subt
                inner join aoidata.stocker_route stkr on stkr.id = subt.id_last_route
                where
                subt.barcode = hp.panel_barcode
                order by subt.created_at desc limit 1
            ) as ultima_ruta
            "))
            ->from("aoidata.history_inspeccion_panel as hp")
            ->where("hp.id_maquina",$this->idMaquina)
            ->whereRaw("hp.created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
            ->whereIn("hp.created_time",function($sub)
            {
                $sub->select(DB::raw($this->inspectionMinOrMax."(created_time)"))
                    ->from("aoidata.history_inspeccion_panel")
                    ->where("id_maquina",$this->idMaquina)
                    ->whereRaw('panel_barcode = hp.panel_barcode')
                    ->whereRaw("created_date = hp.created_date")
                    ->groupBy("panel_barcode")
                    ->groupBy("id_maquina");
            });

            // Filtro horario
            if($this->filterPeriod)
            {
               $q = $q->whereRaw("SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (60*60)) * (60*60)) = '$this->filterPeriod' ");
            }

        $this->runQuery($q);

        return $this->inspecciones;
    }

    // Primer inspeccion creada desde su creacion
    public function panelFirstGlobalApparition()
    {
        $q = PanelHistory::select(DB::raw("
            hp.*,
            (
                select trans_ok from `aoidata`.`transaccion_wip` as subt where
                subt.barcode = hp.panel_barcode
            ) as trans_ok,
            SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (60*60)) * (60*60)) AS periodo
	        "))
            ->from("aoidata.history_inspeccion_panel as hp")
            ->join('aoidata.inspeccion_panel as p', DB::raw('hp.id_panel_history'), '=', DB::raw('p.first_history_inspeccion_panel'))

            ->where('hp.id_maquina',$this->idMaquina)
            ->whereRaw("hp.created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

        // Filtro horario
        if($this->filterPeriod)
        {
            $q = $q->whereRaw("SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (60*60)) * (60*60)) = '$this->filterPeriod' ");
        }

        if($this->filterOp)
        {
            $q = $q->whereRaw("hp.inspected_op = '$this->filterOp' ");
        }

        $this->runQuery($q);

        return $this->inspecciones;
    }

    // Lista de inspecciones (la primer inspeccion global) de la op solicitada
    public function panelFirstGlobalApparitionByOp()
    {
        $q = PanelHistory::select(DB::raw("
            hp.*,
            (
                select trans_ok from `aoidata`.`transaccion_wip` as subt where
                subt.barcode = hp.panel_barcode
            ) as trans_ok
	        "))
            ->from("aoidata.history_inspeccion_panel as hp")
            ->join('aoidata.inspeccion_panel as p', DB::raw('hp.id_panel_history'), '=', DB::raw('p.first_history_inspeccion_panel'))

            ->whereRaw("hp.inspected_op = '$this->filterOp' ")
            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

        $this->runQuery($q);

        return $this->inspecciones;
    }

    // Lista de inspecciones (la primer inspeccion global) de la op solicitada
    public function bloqueFirstGlobalApparition()
    {
        $q = PanelHistory::select(DB::raw("
            hp.turno,
            hp.inspected_op,
            hp.created_date,
            hb.*,
            (
                select trans_ok from `aoidata`.`transaccion_wip` as subt where
                subt.barcode = hb.barcode
                order by subt.created_at  desc limit 1
            ) as trans_ok,
            SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (60*60)) * (60*60)) AS periodo
	        "))
            ->from("aoidata.history_inspeccion_panel as hp")
            ->join('aoidata.inspeccion_panel as p', DB::raw('hp.id_panel_history'), '=', DB::raw('p.first_history_inspeccion_panel'))
            ->join('aoidata.history_inspeccion_bloque as hb', DB::raw('hb.id_panel_history'), '=', DB::raw('hp.id_panel_history'))

            ->where('hp.id_maquina',$this->idMaquina)
            ->whereRaw("hp.created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")

            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

        $this->runQuery($q);

        return $this->inspecciones;
    }

    // Muestra los errores reales de la primer inspeccion global, en periodos de 15 min
    public function queryDefectInspectionRange($allMachine=true)
    {
        $q = PanelHistory::select(DB::raw("
            hp.id_maquina,
            hp.created_date,
            SUM(hp.reales) as errores,
            SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (15*60)) * (15*60)) AS periodo
	        "))
            ->from("aoidata.history_inspeccion_panel as hp")
            ->join('aoidata.inspeccion_panel as p', DB::raw('hp.id_panel_history'), '=', DB::raw('p.first_history_inspeccion_panel'))

            ->whereRaw("hp.created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
            ->groupBy('periodo')

            ->groupBy(DB::raw('hp.id_maquina'))
            ->groupBy(DB::raw('hp.created_date'))

            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

            // Filtro de maquina
            if(!$allMachine)
            {
                $q = $q->where('hp.id_maquina',$this->id_maquina);
            }

        return $q;
    }

    private function runQuery(Builder $query)
    {
        if($this->paginate)
        {
            $this->inspecciones = $query->paginate($this->porPagina);
            $this->filas = $this->inspecciones->total();
        } else
        {
            $this->inspecciones = $query->get();
        }
    }
}