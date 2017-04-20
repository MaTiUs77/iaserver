<?php
namespace IAServer\Http\Controllers\Molinete\Model;

use Illuminate\Database\Eloquent\Model;

class TarjResult extends Model
{
    protected $connection = 'molinete';
    protected $table = 'TarjResult';

    public $timestamps = false;
}
