<?php

namespace IAServer\Http\Controllers\P2i\Model;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'p2i.general';

    public $timestamps = false;
}
