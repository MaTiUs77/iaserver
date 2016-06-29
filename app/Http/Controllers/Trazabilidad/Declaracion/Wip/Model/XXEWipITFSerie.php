<?php
namespace IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model;

use Illuminate\Database\Eloquent\Model;

class XXEWipITFSerie extends Model
{
    protected $connection = 'traza';
    protected $table = 'XXE_WIP_ITF_SERIE';

    public $timestamps = false;

    public function trans_ok_det()
    {
        return $this->hasOne('IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\TransOkDet', 'id', 'trans_ok');
    }
}
