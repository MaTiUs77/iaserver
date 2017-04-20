<?php

namespace IAServer\Http\Controllers\Scrap\Model;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $connection = "npmpicker";
    protected $table = "stat";
}
