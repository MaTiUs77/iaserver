<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;

class Faultcode extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.rns_faultcode';

}
