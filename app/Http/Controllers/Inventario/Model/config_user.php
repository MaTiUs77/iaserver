<?php

namespace IAServer\Http\Controllers\Inventario\Model;

use Illuminate\Database\Eloquent\Model;

class config_user extends Model
{
    protected $connection = 'inventario';
    protected $table = 'config_user';

    public $timestamps = false;

}
