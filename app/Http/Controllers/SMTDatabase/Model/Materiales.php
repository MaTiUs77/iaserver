<?php

namespace IAServer\Http\Controllers\SMTDatabase\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Materiales extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'smtdatabase.materiales';

    public function joinMaterialIndex()
    {
        return $this->hasMany('IAServer\Http\Controllers\SMTDatabase\Model\MaterialIndex','id_material','id');
    }

    public static function findComponente($componente,$likeMode = false)
    {
        $sql = self::select(DB::raw("
            i.modelo,
	        m.*
	        "))
            ->from("smtdatabase.materiales as m")
            ->join( 'smtdatabase.material_index as mi', DB::raw( 'mi.id_material' ), '=', DB::raw( 'm.id' ) )
            ->join( 'smtdatabase.ingenieria as i', DB::raw( 'i.id' ), '=', DB::raw( 'mi.id_ingenieria' ) );

        if($likeMode) {
            $sql = $sql->where('m.componente','like',"4-651-%$componente%");
        } else
        {
            $sql = $sql->where('m.componente',$componente);
        }

        $sql = $sql->groupBy([
            'i.modelo','m.componente','m.descripcion_componente','m.asignacion'
        ]);

        return $sql;
    }

    public static function findSemielaborado($semielaborado)
    {
        $sql = self::findComponent($semielaborado,true);
        return $sql;
    }

    public static function allSemielaboradoByModelo($modelo)
    {
        $sql = self::select(DB::raw("
            i.modelo,
            i.lote,
	        m.*
	        "))
            ->from("smtdatabase.materiales as m")
            ->join( 'smtdatabase.material_index as mi', DB::raw( 'mi.id_material' ), '=', DB::raw( 'm.id' ) )
            ->join( 'smtdatabase.ingenieria as i', DB::raw( 'i.id' ), '=', DB::raw( 'mi.id_ingenieria' ) )
            ->where('i.modelo',$modelo)
            ->where('m.componente','like',"4-651-%");

        return $sql;
    }
}
