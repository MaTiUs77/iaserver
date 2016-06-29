<?php
namespace IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model;

use Illuminate\Database\Eloquent\Model;

class XXEWipITFSerieHistory extends Model
{
    protected $connection = 'traza';
    protected $table = 'XXE_WIP_ITF_SERIE_History';

    public $timestamps = false;

    public function transok()
    {
        return $this->hasOne('IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\TransOkDet', 'id', 'trans_ok');
    }
}
