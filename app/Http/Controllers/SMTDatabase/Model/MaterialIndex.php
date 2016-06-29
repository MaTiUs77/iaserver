<?php

namespace IAServer\Http\Controllers\SMTDatabase\Model;

use Illuminate\Database\Eloquent\Model;

class MaterialIndex extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'smtdatabase.material_index';

    public function ingenieria()
    {
        return $this->hasOne('IAServer\Http\Controllers\SMTDatabase\Model\Ingenieria','id','id_ingenieria');
    }
}
