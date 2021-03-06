<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Carbon\Carbon;
use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PanelHistory extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.history_inspeccion_panel';
    public $timestamps = false;

    /**
     * Relacion de history_inspeccion_panel.id_maquina con maquina.id
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function maquina()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Maquina', 'id', 'id_maquina');
    }

    public function joinBloques()
    {
        return $this->hasMany('IAServer\Http\Controllers\Aoicollector\Model\BloqueHistory', 'id_panel_history', 'id_panel_history');
    }

    public function joinPanel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Panel', 'id', 'id');
    }

    public function joinFirstInspection()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\PanelHistory', 'id_panel_history', 'first_history_inspeccion_panel');
    }

    public function joinLastInspection()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\PanelHistory', 'id_panel_history', 'last_history_inspeccion_panel');
    }

    public function twip()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip', 'id_panel', 'id');
    }

    public function joinStockerDetalle()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle', 'id_panel', 'id');
    }

    public function joinProduccion()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Produccion', 'id_maquina', 'id_maquina');
    }

    /**
     * Busca un codigo de bloque en PanelHistory, si no lo encuentra busca en BloqueHistory
     * Si no encontro nada, busca en Panel, donde seguramente se encuentre en estado PENDIENTE
     *
     * @param string $barcode
     * @return mixed
     */
    public static function buscar($barcode)
    {
        // Primero busca en PanelHistory
        $panel = self::buscarPanel($barcode);
        if(count($panel)>0)
        {
            return $panel;
        } else
        {
            // Luego busca en BloqueHistory
            $bloque = BloqueHistory::buscar($barcode);

            if (count($bloque) > 0)
            {
                $panel_barcode = $bloque->first()->panel->panel_barcode;
                return self::buscarPanel($panel_barcode);
            } else {

                // Si no encontro nada, busca en Panel, donde seguramente se encuentre en estado PENDIENTE
                $panel = Panel::buscarPanel($barcode);
                if(count($panel)>0)
                {
                    return $panel;
                }
            }
        }
    }

    /**
     * Busca el barcode en PanelHistory
     *
     * @param string $barcode
     * @return mixed
     */
    public static function buscarPanel($barcode)
    {
        return self::where('panel_barcode',$barcode)
            ->orderBy('created_date','desc')
            ->orderBy('created_time','desc')
            ->leftJoin('aoidata.maquina', 'maquina.id','=','id_maquina')
            ->select(['history_inspeccion_panel.*','maquina.linea'])
            ->get();
    }

    /**
     * Lista las inspecciones realizadas en una maquina en la fecha solicitada
     *
     * @param int $id_maquina
     * @param string $fecha
     * @param string $op
     * @return null
    public static function listar($id_maquina, $fecha, $op, $minOrMax = 'MAX')
    {
        $q = null;
        $fecha = '"'.$fecha.'"';
        if(empty($op))
        {
           $q = self::select(DB::raw("
                *,
                (
                    select first_history_inspeccion_panel from `aoidata`.`inspeccion_panel` as subp where
                    subp.panel_barcode = hp.panel_barcode
                ) as first_history_inspeccion_panel,
                (
                    select trans_ok from `aoidata`.`transaccion_wip` as subt where
                    subt.barcode = hp.panel_barcode
                ) as trans_ok
                "))
                ->from("aoidata.history_inspeccion_panel as hp")
                ->where("hp.id_maquina",$id_maquina)
                ->whereRaw("hp.created_date = $fecha")
                ->whereIn("hp.created_time",function($sub) use($fecha, $id_maquina, $minOrMax)
                {
                    $sub->select(DB::raw($minOrMax."(created_time)"))
                        ->from("aoidata.history_inspeccion_panel")
                        ->where("id_maquina",$id_maquina)
                        ->whereRaw('panel_barcode = hp.panel_barcode')
                        ->whereRaw("created_date = $fecha")
                        ->groupBy("panel_barcode")
                        ->groupBy("id_maquina");
                });
        } else
        {
            $q = self::listarOp($id_maquina, $fecha, $op);
        }

        return $q;
    }
     */
/*
    public static function listarOp($id_maquina, $fecha, $op)
    {
        $q = null;
        $fecha = '"'.$fecha.'"';

        $q = self::from("aoidata.history_inspeccion_panel as p")
            ->where('p.inspected_op',$op)
            ->whereIn('p.created_time',function($sub) use($fecha, $id_maquina, $op)
            {
                $sub->select(DB::raw("MAX(created_time)"))
                    ->from("aoidata.history_inspeccion_panel")
                    ->where("inspected_op",$op)
                    ->whereRaw('panel_barcode = p.panel_barcode')
                    ->groupBy("panel_barcode")
                    ->groupBy("id_maquina");
            });

        return $q;
    }
*/
    public function scopePeriod($query, $idMaquinaOrOP, $maxOrmin='MAX', $fecha='CURDATE()',$minutes=60)
    {
        $id_maquina = null;
        $op = null;
        if(is_numeric($idMaquinaOrOP))
        {
            $id_maquina = $idMaquinaOrOP;
        } else
        {
            $op = $idMaquinaOrOP;
        }

        if($fecha!='CURDATE()')
        {
            $fecha = '"'.$fecha.'"';
        }

        $q = $this->select(DB::raw("
            COUNT(*) as total ,
	        p.inspected_op as op,
            YEAR(p.created_date) as anio,
            MONTH(p.created_date) as mes,
            DAY(p.created_date) as dia,
        	SEC_TO_TIME((TIME_TO_SEC(p.created_time) DIV (".$minutes."*60)) * (".$minutes."*60)) AS periodo
	        "))
            ->from("aoidata.history_inspeccion_bloque as b")
            ->join( 'aoidata.history_inspeccion_panel as p', DB::raw( 'p.id_panel_history' ), '=', DB::raw( 'b.id_panel_history' ) );

        if($id_maquina!=null)
        {
            $q = $q->where('p.id_maquina',$id_maquina)
                    ->whereRaw("p.created_date = $fecha");
        } else
        {
            $q = $q->where('p.inspected_op',$op);
        }

            $q = $q->whereIn('p.created_time',function($sub) use($fecha, $id_maquina, $op, $maxOrmin)
            {
                $sub = $sub->select(DB::raw($maxOrmin."(created_time)"))
                    ->from("aoidata.history_inspeccion_panel")
                    ->whereRaw('panel_barcode = p.panel_barcode')
                    ->groupBy("panel_barcode")
                    ->groupBy("id_maquina");

                if($id_maquina!=null)
                {
                    $sub = $sub->where('id_maquina',$id_maquina)
                        ->whereRaw("created_date = $fecha");
                } else
                {
                    $sub = $sub->where('p.inspected_op',$op);
                }
            })
            ->groupBy("periodo")
            ->groupBy(DB::raw('p.inspected_op'))
            ->groupBy(DB::raw('p.created_date'))
            ->orderBy(DB::raw('p.created_date'),'asc')
            ->orderBy(DB::raw('p.created_time'),'asc');

        return $q;
    }

    public static function periodFirstApparation($idMaquinaOrOP, $maxOrmin='MAX', Carbon $desdeCarbon=null, Carbon $hastaCarbon=null,$minutes=60)
    {
        $id_maquina = null;
        $op = null;
        if(is_numeric($idMaquinaOrOP))
        {
            $id_maquina = $idMaquinaOrOP;
        } else
        {
            $op = $idMaquinaOrOP;
        }

        if($desdeCarbon==null)
        {
            $desdeCarbon = Carbon::now();
        }

        $q = self::select(DB::raw("
            COUNT(*) as total ,
	        p.inspected_op as op,
            YEAR(p.created_date) as anio,
            MONTH(p.created_date) as mes,
            DAY(p.created_date) as dia,
        	SEC_TO_TIME((TIME_TO_SEC(p.created_time) DIV (".$minutes."*60)) * (".$minutes."*60)) AS periodo
	        "))
            ->from("aoidata.history_inspeccion_bloque as b")
            ->join( 'aoidata.history_inspeccion_panel as p', DB::raw( 'p.id_panel_history' ), '=', DB::raw( 'b.id_panel_history' ) );

        if($id_maquina!=null)
        {
            $q = $q->where('p.id_maquina',$id_maquina)
                ->whereRaw("p.created_date = '".$desdeCarbon->toDateString()."'");
        } else
        {
            $q = $q->where('p.inspected_op',$op);
        }
        $q = $q->whereIn('p.created_date',function($sub) use($id_maquina, $op, $maxOrmin) {
            $sub = $sub->select(DB::raw($maxOrmin."(created_date)"))
                ->from("aoidata.history_inspeccion_panel")
                ->whereRaw('panel_barcode = p.panel_barcode')
                ->groupBy("panel_barcode")
                ->groupBy("id_maquina");

            if($id_maquina!=null)
            {
                $sub = $sub->where('id_maquina',$id_maquina);
            } else
            {
                $sub = $sub->where('p.inspected_op',$op);
            }
        });

        $q = $q->whereIn('p.created_time',function($sub) use($desdeCarbon, $id_maquina, $op, $maxOrmin){
            $sub = $sub->select(DB::raw($maxOrmin."(created_time)"))
                ->from("aoidata.history_inspeccion_panel")
                ->whereRaw('panel_barcode = p.panel_barcode')
                ->groupBy("panel_barcode")
                ->groupBy("id_maquina");

            if($id_maquina!=null)
            {
                $sub = $sub->where('id_maquina',$id_maquina)
                    ->whereRaw("created_date = '".$desdeCarbon->toDateString()."'");
            } else
            {
                $sub = $sub->where('p.inspected_op',$op);
            }
        })
        ->groupBy("periodo")
        ->groupBy(DB::raw('p.inspected_op'))
        ->groupBy(DB::raw('p.created_date'))
        ->orderBy(DB::raw('p.created_date'),'asc')
        ->orderBy(DB::raw('p.created_time'),'asc');

        return $q;
    }

    public static function produccionByRange($id_maquina, $maxOrmin='MIN', Carbon $desdeCarbon = null, Carbon $hastaCarbon= null, $minutes=60)
    {
        $apparition = null;

        switch($maxOrmin)
        {
            case 'MIN':
                $apparition = 'first_history_inspeccion_panel';
                break;
            default:
                $apparition = 'last_history_inspeccion_panel';
                break;
        }

        if($desdeCarbon == null)  {
            $desdeCarbon = Carbon::now();
        }

        if($hastaCarbon == null)  {
            $hastaCarbon = Carbon::now();
        }

        $q = self::select(DB::raw("
            COUNT(hb.id_panel_history) as placas,
            hp.inspected_op as op,
            hp.created_date,
            hp.turno,
            SEC_TO_TIME((TIME_TO_SEC(hp.created_time) DIV (".$minutes."*60)) * (".$minutes."*60)) AS periodo
	        "))
            ->from("aoidata.history_inspeccion_panel as hp")
            ->join('aoidata.inspeccion_panel as p', DB::raw('hp.id_panel_history'), '=', DB::raw('p.'.$apparition))
            ->join('aoidata.history_inspeccion_bloque as hb', DB::raw('hb.id_panel_history'), '=', DB::raw('hp.id_panel_history'))

            ->where('hp.id_maquina',$id_maquina)
            ->whereRaw("hp.created_date between '".$desdeCarbon->toDateString()."' and '".$hastaCarbon->toDateString()."'")

            ->groupBy(DB::raw('p.created_date'))
            ->groupBy("periodo")
            ->groupBy(DB::raw('hp.inspected_op'))
            ->groupBy(DB::raw('hp.turno'))

            ->orderBy(DB::raw('hp.created_date'),'asc')
            ->orderBy(DB::raw('hp.created_time'),'asc');

        return $q;
    }

    public static function programUsed($id_maquina, $fecha, $turno="")
    {
        $fecha = '"'.$fecha.'"';
        $sql = self::select(DB::raw('programa, id_maquina, inspected_op'))
            ->where('id_maquina',$id_maquina);

        if(!empty($turno))
        {
            $sql = $sql->where('turno',$turno);
        }

        $sql = $sql->whereRaw("created_date = $fecha")
            ->groupBy('programa','inspected_op')
            ->get();

        return $sql;
    }

    public static function programUsedByLine($linea, $fecha, $turno)
    {
        $fecha = '"'.$fecha.'"';
        return self::select(DB::raw('programa, id_maquina, inspected_op'))
            ->leftJoin('aoidata.maquina', 'maquina.linea','=',DB::raw($linea))
            ->where('turno',$turno)
            ->whereRaw("id_maquina = maquina.id")
            ->whereRaw("created_date = $fecha")
            ->groupBy('programa','inspected_op')
            ->orderBy('id_maquina')
            ->get();
    }

    public function cogiscan()
    {
        $cogiscanService= new Cogiscan();
        return $cogiscanService->queryItem($this->panel_barcode);
    }

    public function smt()
    {
        $w = new Wip();
        $smt = SMTDatabase::findOp($this->inspected_op);

        if($smt->semielaborado == null)
        {
            // Obtengo semielaborado desde interfaz
            $wipResult = $w->findOp($this->inspected_op,false,false);
            if(isset($wipResult->wip_ot->codigo_producto))
            {
                $smt->semielaborado = $wipResult->wip_ot->codigo_producto;
                $smt->save();
            }
        }

        return $smt;
    }

    function isSecundario()
    {
        if($this->bloques == $this->joinBloques()->where('etiqueta','V')->count()) {
            return true;
        } else
        {
            return false;
        }
    }
}
