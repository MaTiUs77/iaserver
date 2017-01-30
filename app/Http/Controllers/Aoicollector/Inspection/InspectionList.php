<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InspectionList extends Controller
{
    public $firstApparition = false;

    public $id_maquina = null;

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

    public function __construct($id_maquina, Carbon $desde, Carbon $hasta)
    {
        $this->id_maquina = $id_maquina;
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
        if(is_numeric($pagina)) {
            $this->pagina = $pagina;
        } else
        {
            $this->pagina = 1;
        }
    }

    public function find()
    {
        $list = "";
        if($this->firstApparition)
        {
            $list = $this->queryPanelInspectionRange();
        } else
        {
            $list = $this->queryMachineInspectionRange();
        }

        $this->inspecciones = $list->paginate($this->porPagina);
        $this->filas = $this->inspecciones->total();

        $this->programasUsados();
    }

    public function programasUsados()
    {
        if(empty($this->filterOp))
        {
            $this->programas = $this->queryProgramUsed();
        }
    }

    private function queryMachineInspectionRange()
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
            ) as trans_ok
            "))
            ->from("aoidata.history_inspeccion_panel as hp")
            ->where("hp.id_maquina",$this->id_maquina)
            ->whereRaw("hp.created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
            ->whereIn("hp.created_time",function($sub)
            {
                $sub->select(DB::raw($this->inspectionMinOrMax."(created_time)"))
                    ->from("aoidata.history_inspeccion_panel")
                    ->where("id_maquina",$this->id_maquina)
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

        return $q;
    }

    private function queryProgramUsed($turno="")
    {
        $sql = PanelHistory::select(DB::raw('programa, id_maquina, inspected_op'))
            ->where('id_maquina',$this->id_maquina);

        if(!empty($turno))
        {
            $sql = $sql->where('turno',$turno);
        }

        $sql = $sql->whereRaw("created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
            ->groupBy('programa','inspected_op')
            ->get();

        return $sql;
    }

    private function queryPanelInspectionRange()
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

            ->where('hp.id_maquina',$this->id_maquina)
            ->whereRaw("hp.created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

            // Filtro horario
            if($this->filterPeriod)
            {
                $q = $q->whereRaw("SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (60*60)) * (60*60)) = '$this->filterPeriod' ");
            }

        return $q;
    }

    /*
     * Muestra los errores reales de la primer y unica inspeccion del dia, en periodos de 15 min
     */
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

            //->where('hp.id_maquina',$this->id_maquina)
            ->whereRaw("hp.created_date between '".$this->desdeCarbon->toDateString()."' and '".$this->hastaCarbon->toDateString()."'")
            ->groupBy('periodo')

            ->groupBy(DB::raw('hp.id_maquina'))
            ->groupBy(DB::raw('hp.created_date'))

            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

            // Filtro horario
            if(!$allMachine)
            {
                $q = $q->where('hp.id_maquina',$this->id_maquina);
            }

        return $q;
    }
}