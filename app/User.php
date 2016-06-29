<?php

namespace IAServer;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;


class User extends Authenticatable
{
    use EntrustUserTrait; //hacemos uso del trait en la clase User para hacer uso de sus métodos

    protected $fillable = [
        'name', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    //establecemos las relaciones con el modelo Role, ya que un usuario puede tener varios roles
    //y un rol lo pueden tener varios usuarios
    public function roles(){
        return $this->belongsToMany('IAServer\Http\Controllers\Auth\Entrust\Role');
    }

    public function profile() {
        return $this->hasOne('IAServer\Http\Controllers\Auth\Entrust\Profile','user_id');
    }

    public function hasProfile() {
        if(count($this->profile)) {
            return true;
        } else {
            return false;
        }
    }
}

/*
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;

    protected $table = 'iaserver.users';

    protected $fillable = ['name', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function profile() {
        return $this->hasOne('IAServer\Http\Controllers\Auth\Entrust\Profile','user_id');
    }

    public function hasProfile() {
        if(count($this->profile)) {
            return true;
        } else {
            return false;
        }
    }
}*/
