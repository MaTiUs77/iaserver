<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class materiales extends Model
{
    protected $connection = 'inventario';
    protected $table = 'materiales';

    protected $fillable = array('codigo','descripcion');
    public $timestamps = false;

}
