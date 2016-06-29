<?php

namespace IAServer\Http\Controllers\SMTDatabase\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Materiales extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'smtdatabase.materiales';

    public function materialIndex()
    {
        return $this->hasMany('IAServer\Http\Controllers\SMTDatabase\Model\MaterialIndex','id_material','id');
    }

    public static function modelsWithComponente($componente)
    {
        $sql = self::select(DB::raw("
            i.modelo,
	        m.*
	        "))
            ->from("smtdatabase.materiales as m")
            ->join( 'smtdatabase.material_index as mi', DB::raw( 'mi.id_material' ), '=', DB::raw( 'm.id' ) )
            ->join( 'smtdatabase.ingenieria as i', DB::raw( 'i.id' ), '=', DB::raw( 'mi.id_ingenieria' ) )
            ->where('m.componente',$componente)
            ->groupBy([
                'i.modelo','m.componente','m.descripcion_componente','m.asignacion'
            ]);
        return $sql;
    }
}
