<?php

namespace IAServer\Http\Controllers\IAServer\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'iaserver.menu';
    public $timestamps = false;
}
