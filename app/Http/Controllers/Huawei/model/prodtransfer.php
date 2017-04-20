<?php

namespace IAServer\Http\Controllers\Huawei\Model;

use Illuminate\Database\Eloquent\Model;

class prod_transfer extends Model
{
    protected $connection = 'tracenewsan';
    protected $table = 'prodtransfer';

    public $timestamps = false;

}
