<?php

namespace IAServer\Http\Controllers\Aoicollector\Pizarra\PizarraCone;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProduccionCone extends Model
{
    protected $connection = 'pizarra';
    protected $table = 'produccion';

    public function panel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Pizarra\PizarraCone\PanelCone','id','id_panel');
    }

    public function panel_changeover()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Pizarra\PizarraCone\PanelCone','id','id_panel_1');
    }

    public static function prepareProyectado(Collection $proyectado, $turnos=array())
    {
        foreach ($proyectado as $proy) {
            $hora = (int)$proy->horario;
            if ($hora >= $turnos['M']['desde'] && $hora < $turnos['M']['hasta']) {
                $proy->turno = 'M';
            } else {
                $proy->turno = 'T';
            }

            $div_proyectado = $proy->proyectado;
            $div_proyectado_changeover = $proy->proyectado_1;
            $div_proyectado_period = $div_proyectado + $div_proyectado_changeover;

            $proy->proyectado = $div_proyectado_period;
            $proy->produccion = $proy->p_real + $proy->p_real_1;

            $proy->mes = str_pad($proy->mes, 2, "0", STR_PAD_LEFT);
            $proy->dia = str_pad($proy->dia, 2, "0", STR_PAD_LEFT);

            $proy->fecha = "$proy->anio-$proy->mes-$proy->dia";
            $proy->periodo = str_pad($proy->horario, 2, "0", STR_PAD_LEFT).":00:00";
            $proy->chartPeriodo = "$proy->fecha $proy->periodo";

            // Evito divisiones por 0
            if($div_proyectado <= 0) {
                $div_proyectado_period = 1;
            }

            if($div_proyectado_changeover <= 0) {
                if($div_proyectado_period <=0) {
                    $div_proyectado_period = 1;
                }
            }

//            $proy->porcentaje = number_format((($proy->p_real/ $div_proyectado) * 100), 1, '.', '');
  //          $proy->porcentaje_changeover = number_format((($proy->p_real_1 / $div_proyectado_changeover) * 100), 1, '.', '');
            $proy->porcentaje  = number_format(((($proy->p_real + $proy->p_real_1) / $div_proyectado_period) * 100), 1, '.', '');
        }

        //$proyectado = $proyectado->groupBy('turno');

        return $proyectado;
    }
}
