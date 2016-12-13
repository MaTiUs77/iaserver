<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod;

use IAServer\Http\Controllers\Aoicollector\Model\Panel;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\TrazaStocker;
use IAServer\Http\Controllers\Controldeplacas\DatosController;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Controllers\Trazabilidad\Sfcs\Sfcs;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ProduccionWatcher extends Controller
{
    public $withWip = true;
    public $withSmt = true;
    public $withSmtRoute = true;
    public $withSfcsRoute = true;
    public $withPlacas = true;
    public $withTransaction = true;
    public $withInspector = true;
    public $withPeriod = true;
    public $withStocker = true;
    public $withAllStocker = false;

    public $error = null;
    public $mode = null;

    public function byMachine($aoibarcode)
    {
        $output = [];

        if (is_numeric($aoibarcode)) {
            $produccion = Produccion::byLine($aoibarcode);
            $this->mode = 'byLine';
        } else {
            $produccion = Produccion::barcode($aoibarcode);
            $this->mode = 'barcode';
        }

        // Existe produccion de maquina
        if(isset($produccion->barcode))
        {
            $forProduccion[] = $produccion;
            $produccion = collect($forProduccion);
        }

        // Se solicita informacion de linea, tiene mas de una AOI
        if (count($produccion) > 0) {
            foreach ($produccion as $prod) {
                // Adhiero informacion de inspector asignado a la linea
                if ($this->withInspector) {
                    $prod->inspector = $this->getInspectorById($prod->id_user);
                }

                $inspected = $this->getAllInspectedOpByMachineId($prod->id_maquina);

                // Adhiero informacion de SMT y control de placas
                if ($this->withSmt) {
                    $inspected = $this->addSmtToAllInspectedOpByMachineId($inspected);

                    if(isset($prod->op))
                    {
                        $locateInspectedOpInfo = $inspected->where('inspected_op',$prod->op);
                        if(count($locateInspectedOpInfo )>0)
                        {
                            $prod->smt = $locateInspectedOpInfo->first()->smt;
                            $prod->controldeplacas = $locateInspectedOpInfo->first()->controldeplacas;
                        } else
                        {
                            $smt = SMTDatabase::findOp($prod->op);

                            if (isset($smt->modelo)) {
                                $smt->registros = Panel::where('inspected_op', $prod->op)->count();

                                $div = $smt->qty;
                                if ($div == 0) {
                                    $div = 1;
                                }
                                $smt->porcentaje = number_format((($smt->prod_aoi / $div) * 100), 1, '.', '');
                                $smt->restantes = $smt->prod_aoi - $smt->qty;
                                $prod->smt = $smt;
                                $prod->controldeplacas = DatosController::salidaByOp($prod->op);
                            }
                        }
                    }
                }

                if (isset($prod->op)) {
                    if($this->withWip)
                    {
                        $w = new Wip();
                        $wip = $w->findOp($prod->op, $this->withTransaction);

                        $prod->wip = $wip;
                    }

                    // Adhiero informacion de SFCS
                    if ($this->withSfcsRoute) {
                        $sf = new Sfcs();
                        $sfcs = $sf->declareMode($prod->op, $prod->line_id, $prod->modelo_id, $prod->puesto_id);

                        $prod->sfcs = $sfcs;
                    }

                    // Adhiero informacion de stockers
                    $stkctrl = new TrazaStocker();
                    if ($this->withStocker) {
                        $stkctrl = new TrazaStocker();
                        $prod->stocker = $stkctrl->stockerInfoById($prod->id_stocker);
                    }

                    // Adhiero todos los stockers asignados a esa OP
                    if ($this->withAllStocker) {
                        $allstocker = Stocker::where('op', $prod->op)->get();

                        $stockerList = [];

                        if (count($allstocker) > 0) {
                            foreach ($allstocker as $stk) {
                                $stkctrl = new TrazaStocker();
                                $stockerList[] = $stkctrl->stockerInfoById($stk->id);
                            }
                        }

                        $prod->allstocker = $stockerList;
                    }

                    // Adhiero informacion de periodo de inspecciones
                    if ($this->withPeriod && $prod->id_maquina != null) {
                        $period = PanelHistory::produccionByRange($prod->id_maquina, 'MIN')->get();
                        $prod->period = $period;
                    }

                } else {
                    $error = "No existe configuracion de OP para esta maquina";
                }

                if($this->mode=='barcode')
                {
                    $produccion = $produccion->first();
                }

                $output = compact('error', 'inspected', 'produccion');
            }

        } else {
            $error = 'No hay resultados';
            $output = compact('error');
        }

        return $output;
    }

    public function getInspectorById($id_user)
    {
        $inspector = null;

        if(isset($id_user))
        {
            $inspector = User::find($id_user);
            if ($inspector->hasProfile()) {
                $inspector->fullname = $inspector->profile->fullname();
            } else
            {
                $inspector->fullname = $inspector->name;
            }
        }

        return $inspector;
    }

    public function getAllInspectedOpByMachineId($id_maquina)
    {
        $sql = PanelHistory::select(DB::raw('
            `inspected_op`,
            MIN(`created_time`) as first_inspection,
            MAX(`created_time`) as last_inspection
            '))
            ->where('id_maquina',$id_maquina)
            ->whereRaw(DB::raw('created_date = CURDATE() '))
            ->groupBy('inspected_op');

        return $sql->get();
    }

    public function addSmtToAllInspectedOpByMachineId(Collection $inspected)
    {
        foreach($inspected as $insop)
        {
            $smt = SMTDatabase::findOp($insop->inspected_op);

            if (isset($smt->modelo)) {
                $smt->registros = Panel::where('inspected_op', $insop->inspected_op)->count();

                $div = $smt->qty;
                if ($div == 0) {
                    $div = 1;
                }
                $smt->porcentaje = number_format((($smt->prod_aoi / $div) * 100), 1, '.', '');
                $smt->restantes = $smt->prod_aoi - $smt->qty;
                $insop->smt = $smt;
                $insop->controldeplacas = DatosController::salidaByOp($insop->inspected_op);
            }
        }

        return $inspected;
    }
}