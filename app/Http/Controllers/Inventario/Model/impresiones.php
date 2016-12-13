<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class impresiones extends Model
{
    protected $connection = 'inventario';
    protected $table = 'impresiones';
    protected $primaryKey = 'id_etiqueta';
    public $timestamps = false;

}
