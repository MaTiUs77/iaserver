<?php

namespace IAServer\Http\Controllers\SMTDatabase\Model;

use Illuminate\Database\Eloquent\Model;

class Lotes extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'smtdatabase.lotes';

}
