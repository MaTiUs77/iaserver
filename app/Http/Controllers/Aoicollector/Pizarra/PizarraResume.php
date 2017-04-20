<?php

namespace IAServer\Http\Controllers\Aoicollector\Pizarra;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\InspectionList;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Pizarra\PizarraCone\ProduccionCone;
use IAServer\Http\Controllers\Aoicollector\Pizarra\Src\PizarraItemObj;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class PizarraResume extends Controller
{
    public $error = null;

    public $proyectado = null;
    public $produccion = null;

    public $byPeriod = array();
    public $byOp = array();
    public $smt = array();

    public $proyectadoCone = null;
    public $produccionAoi= array();

    public $produccionLine= null;

    public $linea = null;
    public $desdeCarbon = null;
    public $hastaCarbon = null;

    public $config = [
        'M' => [
            'desde' => 1,
            'hasta' => 15
        ],
        'T' => [
            'desde' => 15,
            'hasta' => 0
        ],
    ];

    public function __construct($linea,Carbon $desdeCarbon,Carbon $hastaCarbon) {
        $this->desdeCarbon = $desdeCarbon;
        $this->hastaCarbon = $hastaCarbon;
        $this->linea = $linea;

        // Creo -objetos para resumen
        $this->proyectado = new PizarraItemObj();
        $this->produccion = new PizarraItemObj();

        $this->getInfoFromLine();

        if($this->proyectado->cone->total>0)
        {
            $this->produccion->aoi->porcentaje = ($this->produccion->aoi->total / $this->proyectado->cone->total) * 100;
            $this->produccion->cone->porcentaje = ($this->produccion->cone->total / $this->proyectado->cone->total) * 100;
        }

        foreach($this->byOp as $op => $data) {
            $this->smt[$op] = SMTDatabase::findOp($op);
        }

        return $this;
    }

    private function getInfoFromLine(){
        if(is_numeric($this->linea)) {
            /*
            Obtengo el la produccion proyectada para esa linea
            Los datos por ahora solo funciona con las lineas con AOI
            */

            $this->proyectadoCone = $this->prepareProyectadoCone();

            $this->aoiProduccionByLine();

            $this->computeProyectedWithProduccion();

            /*$this->proyectado->cone->reporteIncompleto->M = $this->calculateIncompleteReport('M');
            $this->proyectado->cone->reporteIncompleto->T = $this->calculateIncompleteReport('T');

            if($this->proyectado->cone->total > 0) {
                $this->produccion->aoi->porcentaje = number_format((($this->produccion->aoi->total / $this->proyectado->cone->total ) * 100), 1, '.', '');
            }*/
        } else {
            $this->error = "La linea no es valida";
        }
    }

    // Computa los datos de produccion contra los datos de proyeccion
    private function computeProyectedWithProduccion()
    {
        $this->proyectado->cone->faltante = $this->proyectado->cone->total - $this->produccion->aoi->total;
        $this->proyectado->cone->faltanteM = $this->proyectado->cone->M  - $this->produccion->aoi->M;
        $this->proyectado->cone->faltanteT = $this->proyectado->cone->T - $this->produccion->aoi->T;

        $this->produccion->cone->faltante = $this->proyectado->cone->total - $this->produccion->cone->total;
        $this->produccion->cone->faltanteM = $this->proyectado->cone->M  - $this->produccion->cone->M;
        $this->produccion->cone->faltanteT = $this->proyectado->cone->T - $this->produccion->cone->T;

        foreach ($this->byPeriod as $periodo => $data)
        {
            $produccionAoi = count($data->aoi);

            $cone = collect($data->cone);
            $proyectado = $cone->sum('proyectado');
            $produccionCone = $cone->sum('produccion');

            $eficienciaAoi = 0;
            $eficienciaCone = 0;

            if($proyectado>0)
            {
                $eficienciaAoi = ($produccionAoi / $proyectado) * 100;
                $eficienciaCone = ($produccionCone / $proyectado) * 100;
            }
            $this->byPeriod[$periodo]->proyectado = $proyectado;
            $this->byPeriod[$periodo]->eficiencia->aoi = $eficienciaAoi;
            $this->byPeriod[$periodo]->produccion->aoi = $produccionAoi;

            $this->byPeriod[$periodo]->eficiencia->cone = $eficienciaCone;
            $this->byPeriod[$periodo]->produccion->cone = $produccionCone;
        }

        ksort($this->byPeriod);

        $this->byPeriod = collect($this->byPeriod);
    }

    private function aoiProduccionByLine()
    {
        // Obtengo todas las AOI de esa linea ( trae 2 AOI en casos de dual lines)
        $produccion = Produccion::byLine($this->linea);

        // Recorro las AOI asignadas a esa linea
        foreach ($produccion as $aoi) {
            if($this->produccionLine == null) {
                $this->produccionLine = $aoi;
            }

            $inspectionList = new InspectionList($this->desdeCarbon, $this->hastaCarbon);
            $inspectionList->setIdMaquina($aoi->id_maquina);
            $inspectionList->bloqueFirstGlobalApparition();
            $inspectionByOp = $inspectionList->inspecciones->groupBy('inspected_op');

            // Recorre todas las inspecciones agrupadas por OP de la maquina
            foreach($inspectionByOp as $op => $items) {

                $produccionTotal = $items->count('barcode');
                $produccionM = $items->where('turno','M')->count('barcode');
                $produccionT = $items->where('turno','T')->count('barcode');

                $items->each(function($x) use($op) {
                    $x->chartPeriodo = "$x->created_date $x->periodo";
                    if(!isset($this->byPeriod[$x->chartPeriodo])) {
                        $this->byPeriod[$x->chartPeriodo] = (object)[
                            'aoi' => [],
                            'cone' => [],
                            'turno' => $x->turno,
                            'proyectado' => 0,
                            'eficiencia' => (object) [
                                'aoi' => 0,
                                'cone' => 0,
                            ],
                            'produccion' => (object) [
                                'aoi' => 0,
                                'cone' => 0,
                            ]
                        ];
                    }
                    $this->byPeriod[$x->chartPeriodo]->aoi[] = $x;
                });

                // Inspecciones separadas por OP, adjunta en una misma OP en caso de las lineas duales
                if(isset($this->byOp[$op])) {
                    foreach ($items as $addItem) {
                        $this->byOp[$op][] = $addItem;
                    }
                } else {
                    $this->byOp[$op] = $items;
                }
                // Sumo produccion de OP y de maquina a produccion de jornada
                $this->produccion->aoi->total += $produccionTotal;
                $this->produccion->aoi->M += $produccionM;
                $this->produccion->aoi->T += $produccionT;
            }
        }
    }

    private function calculateIncompleteReport($turno)
    {
        $proyectadoCone = array();

        if(isset($this->proyectadoCone[$turno]))
        {
            // Veo solo horario en CONE
            $proyectadoCone = collect($this->proyectadoCone[$turno])->map(function ($item) {
                return (int) $item->horario;
            });
        }

        // Veo solo horarios en AOI
        $produccionAoi = collect($this->byHour)->map(function ($item) use($turno) {
            if($item->turno==$turno && $item->aoi != null)
            {
                return $item->hora;
            }
        });

        // Obtengo diferencia de reportes
        $diff = $produccionAoi->diff($proyectadoCone);
        $diff = $diff->filter(function($item){
            if($item != null) {
                return $item;
            }
        });

        return $diff->count();
    }

    /***
     * Obtiene lista de reportes de produccion de la fecha solicitada
     * calcula porcentaje de produccion por hora segun lo proyectado
     *
     * @param $linea
     * @param $dateEn
     * @return mixed
     */
    private function prepareProyectadoCone()
    {
        $proyectado = ProduccionCone::where('linea', $this->linea)
            ->whereRaw("dia >= '" . $this->desdeCarbon->day . "' and dia <= '" . $this->hastaCarbon->day . "'")
            ->whereRaw("mes >= '" . $this->desdeCarbon->month . "' and mes <= '" . $this->hastaCarbon->month . "'")
            ->whereRaw("anio >= '" . $this->desdeCarbon->year . "' and anio <= '" . $this->hastaCarbon->year . "'")
            ->whereNotNull('proyectado')
            ->orderBy('dia', 'asc')
            ->orderBy('mes', 'asc')
            ->orderBy('anio', 'asc')
            ->orderBy('horario', 'asc')
            ->get();

        $output = ProduccionCone::prepareProyectado($proyectado, $this->config);

        $this->proyectado->cone->total = $output->sum('proyectado');
        $this->proyectado->cone->M = $output->where('turno', 'M')->sum('proyectado');
        $this->proyectado->cone->T = $output->where('turno', 'T')->sum('proyectado');

        $this->produccion->cone->total = $output->sum('p_real');
        $this->produccion->cone->M = $output->where('turno', 'M')->sum('p_real');
        $this->produccion->cone->T = $output->where('turno', 'T')->sum('p_real');

        $output->each(function ($x) {
            if (!isset($this->byPeriod[$x->chartPeriodo])) {
                $this->byPeriod[$x->chartPeriodo] = (object)[
                    'aoi' => [],
                    'cone' => [],
                    'turno' => $x->turno,
                    'proyectado' => 0,
                    'eficiencia' => (object) [
                        'aoi' => 0,
                        'cone' => 0,
                    ],
                    'produccion' => (object) [
                        'aoi' => 0,
                        'cone' => 0,
                    ]
                ];
            }
            array_push($this->byPeriod[$x->chartPeriodo]->cone, $x);
        });

        return $output;
    }

    private function fillByHourWithProyectadoCone()
    {
        $this->byHour = array();

        $hora = 0;
        for($hora==0;$hora<=23;$hora++)
        {
            if ($hora >= $this->config['M']['desde'] && $hora < $this->config['M']['hasta']) {
                $turno = 'M';
            } else {
                $turno = 'T';
            }

            $this->byHour[$hora] = (object) [
                'turno'=>$turno,
                'hora'=>$hora,
                'cone'=>null,
                'aoi'=>null,
                'ops' => array()
            ];
        }

        // Placas proyectadas en turno mañana en PizarraCone
        if(isset($this->proyectadoCone['M']))
        {
            $this->proyectado->cone->M = collect($this->proyectadoCone['M'])->sum('proyectado');
            $this->makeByHourWithProyectadoCone('M');
        }

        // Placas proyectadas en turno tarde en PizarraCone
        if(isset($this->proyectadoCone['T']))
        {
            $this->proyectado->cone->T = collect($this->proyectadoCone['T'])->sum('proyectado');
            $this->makeByHourWithProyectadoCone('T');
        }

        // Total de placas proyecatadas en ambas jorandas
        $this->proyectado->cone->total = $this->proyectado->cone->M + $this->proyectado->cone->T;
        $this->produccion->cone->total = $this->produccion->cone->M + $this->produccion->cone->T;
    }

    private function makeByHourWithProyectadoCone($turno)
    {
        foreach($this->proyectadoCone[$turno] as $item) {
            if(isset($this->byHour[$item->horario]->cone))
            {
                $this->byHour[$item->horario]->cone->proyectado += $item->proyectado;
                $this->byHour[$item->horario]->cone->produccion += $item->produccion;

                $this->byHour[$item->horario]->cone->porcentaje = number_format((($this->byHour[$item->horario]->cone->produccion / $this->byHour[$item->horario]->cone->proyectado) * 100), 1, '.', '');
            } else
            {
                $this->byHour[$item->horario]->cone = (object) [
                    "proyectado_forced" => null,
                    "proyectado" => $item->proyectado,
                    "produccion" => $item->produccion,
                    "porcentaje" => $item->porcentaje
                ];
            }

            switch($turno)
            {
                case 'M':
                        $this->produccion->cone->M += $item->produccion;
                    break;
                case 'T':
                        $this->produccion->cone->T += $item->produccion;
                    break;
            }
        }
    }
}
