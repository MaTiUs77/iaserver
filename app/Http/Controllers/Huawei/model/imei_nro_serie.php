<?php

namespace IAServer\Http\Controllers\Huawei\Model;

use Illuminate\Database\Eloquent\Model;

class imei_nro_serie extends Model
{
    protected $connection = 'tracenewsan';
    protected $table = 'imei_nro_serie';

    public $timestamps = false;

}
