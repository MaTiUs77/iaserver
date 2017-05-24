<?php

namespace IAServer\Http\Controllers\Memorias\Model;

use Illuminate\Database\Eloquent\Model;

class ZebraMemorias extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'memorias.zebra';
    public $timestamps = false;
}
