<?php

namespace IAServer\Http\Controllers\Ipc\Model;

use Illuminate\Database\Eloquent\Model;

class Certificacion extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'ipc.certificacion';
    public $timestamps = false;

    public function profile()
    {
        return $this->hasOne('IAServer\Http\Controllers\Auth\Entrust\Profile','id','id_perfil');
    }

    public function norma()
    {
        return $this->hasOne('IAServer\Http\Controllers\Ipc\Model\Norma','id_norma','id_norma');
    }

    public function user()
    {
        return $this->hasOne('IAServer\User','id','id_instructor');
    }
}
