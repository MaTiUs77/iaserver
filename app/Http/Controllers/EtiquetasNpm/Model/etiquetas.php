<?php

namespace IAServer\Http\Controllers\EtiquetasNpm\Model;

use Illuminate\Database\Eloquent\Model;

class etiquetas extends Model
{
    protected $connection = 'labelnpm';
    protected $table = 'etiquetas';

    public $timestamps = false;

}
