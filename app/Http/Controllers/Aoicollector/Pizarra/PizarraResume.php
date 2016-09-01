<?php

namespace IAServer\Http\Controllers\Aoicollector\Pizarra;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Pizarra\PizarraCone\ProduccionCone;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class PizarraItemObj extends Controller
{
    public $aoi = null;
    public $cone = null;

    public function  __construct()
    {
        $this->aoi = (object) [
            'total' => 0,
            'M' => 0,
            'T' => 0,
            'porcentaje' => 0,
            'porcentajeM' => 0,
            'porcentajeT' => 0
        ];

        $this->cone = (object) [
            'total' => 0,
            'M' => 0,
            'T' => 0,
            'porcentaje' => 0,
            'porcentajeM' => 0,
            'porcentajeT' => 0,
            'reporteIncompleto' => (object) [
                'M' => 0,
                'T' => 0
            ]
        ];
    }
}

class PizarraResume extends Controller
{
    public $error = null;

    public $proyectado = null;
    public $produccion = null;

    public $byHour = array();
    public $byOp = array();
    public $byTurn = array();

    public $proyectadoCone = null;
    public $produccionAoi= array();

    public $produccionLine= null;

    public $dateEn = null;
    public $config = [
        'M' => [
            'desde' => 3,
            'hasta' => 15
        ],
        'T' => [
            'desde' => 15,
            'hasta' => 3
        ],
    ];

    public function __construct($linea,Carbon $desdeCarbon,Carbon $hastaCarbon)
    {
        $this->dateEn = $desdeCarbon->toDateString(); // Util::dateToEn(Session::get('pizarra_fecha'));

        $this->proyectado = new PizarraItemObj();
        $this->produccion = new PizarraItemObj();

        $this->getInfoFromLine($linea);

        $this->byTurn = collect($this->byHour)->groupBy('turno')->all();

        return $this;
    }

    private function getInfoFromLine($linea)
    {
        if(is_numeric($linea)) {
            /*
            Obtengo el la produccion proyectada para esa linea
            Los datos por ahora solo funciona con las lineas con AOI
            */
            $this->proyectadoCone = $this->prepareProyectadoCone($linea, $this->dateEn);
            $this->fillByHourWithProyectadoCone();

            $this->aoiProduccionByLine($linea);

            $this->proyectado->cone->reporteIncompleto->M = $this->calculateIncompleteReport('M');
            $this->proyectado->cone->reporteIncompleto->T = $this->calculateIncompleteReport('T');

            if($this->proyectado->cone->total > 0) {
                $this->produccion->aoi->porcentaje = number_format((($this->produccion->aoi->total / $this->proyectado->cone->total ) * 100), 1, '.', '');
            }
        } else
        {
            $this->error = "La linea no es valida";
        }
    }

    private function aoiProduccionByLine($linea)
    {
        // Obtengo todas las AOI de esa linea ( trae 2 AOI en casos de dual lines)
        $produccion = Produccion::byLine($linea);

        // Recorro las AOI asignadas a esa linea
        foreach ($produccion as $aoi) {
            if($this->produccionLine == null) {
                $this->produccionLine = $aoi;
            }

            $aoiPeriod = PanelHistory::periodFirstApparation($aoi->id_maquina, 'MIN', $this->dateEn)->get();

            foreach ($aoiPeriod as $period)
            {
                /////////// DEFINO HORA Y PRODUCCION POR OP ///////////
                list($hora, $minuto, $segundo) = explode(':', $period->periodo);

                $hora = (int)$hora;
                if ($hora >= $this->config['M']['desde'] && $hora < $this->config['M']['hasta']) {
                    $period->turno = 'M';
                } else {
                    $period->turno = 'T';
                }
                if (collect($this->byOp)->get($period->op) == null) {
                    $smt = SMTDatabase::findOp($period->op);

                    // Creo op en byOp
                    $this->byOp[$period->op] = (object) [
                        'smt'=>$smt,
                        'produccion' => 0,
                        'produccionM' => 0,
                        'produccionT' => 0,
                        'periodo' => [
                            'M'=>array(),
                            'T'=>array()
                        ],
                    ];
                }
                $this->byOp[$period->op]->produccion += $period->total;

                /////////// SUMA PRODUCCION TOTAL POR TURNO ///////////
                switch($period->turno)
                {
                    case 'M':
                        $this->byOp[$period->op]->produccionM += $period->total;
                        break;
                    case 'T':
                        $this->byOp[$period->op]->produccionT += $period->total;
                        break;
                }

                /////////// SUMA PRODUCCION TOTAL EN DUAL LINES ///////////
                if(isset($this->byOp[$period->op]->periodo[$period->turno][$hora]))
                {
                    $this->byOp[$period->op]->periodo[$period->turno][$hora] += $period->total;
                } else
                {
                    $this->byOp[$period->op]->periodo[$period->turno][$hora] = $period->total;
                }

                /////////// VERIFICA SI EXISTE PRODUCCION AOI ///////////
                if($this->byHour[$hora]->aoi == null)
                {
                    if(isset($this->byHour[$hora]->cone->proyectado) && $this->byHour[$hora]->cone->proyectado>0)
                    {
                        $this->byHour[$hora]->aoi = (object) [
                            "produccion" => $period->total,
                            "porcentaje" => number_format((($period->total / $this->byHour[$hora]->cone->proyectado) * 100), 1, '.', '')
                        ];
                    } else
                    {
                        // No hay proyeccion, no es posible calcular porcentaje de produccion
                        $this->byHour[$hora]->aoi = (object) [
                            "produccion" => $period->total,
                            "porcentaje" => 0
                        ];
                    }
                } else
                {
                    $this->byHour[$hora]->aoi->produccion += $period->total;

                    if($this->byHour[$hora]->cone == null)
                    {
                        $this->byHour[$hora]->aoi->porcentaje = 0;
                    } else
                    {
                        $this->byHour[$hora]->aoi->porcentaje = number_format((($this->byHour[$hora]->aoi->produccion / $this->byHour[$hora]->cone->proyectado) * 100), 1, '.', '');
                    }
                }

                $this->byHour[$hora]->ops[$period->op] = $period->total;

            }

            $aoiPeriod = $aoiPeriod->groupBy('turno');

            if(isset($aoiPeriod['M']))
            {
                $this->produccion->aoi->M += collect($aoiPeriod['M'])->sum('total');
            }

            if(isset($aoiPeriod['T']))
            {
                $this->produccion->aoi->T += collect($aoiPeriod['T'])->sum('total');
            }

            $this->produccion->aoi->total = $this->produccion->aoi->M + $this->produccion->aoi->T;

            $this->produccionAoi[$aoi->barcode] = $aoiPeriod;
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
    private function prepareProyectadoCone($linea, $dateEn)
    {
        list($anio,$mes,$dia) = explode('-',$dateEn);

        $proyectado = ProduccionCone::where('linea', $linea)
            ->whereRaw("dia = '".$dia."'")
            ->whereRaw("mes = '".$mes."'")
            ->whereRaw("anio = '".$anio."'")
            ->whereNotNull('proyectado')
            ->orderBy('horario', 'asc')
            ->get();

        $output = ProduccionCone::prepareProyectado($proyectado,$this->config);

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
