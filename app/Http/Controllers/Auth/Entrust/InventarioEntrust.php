<?php

namespace IAServer\Http\Controllers\Auth\Entrust;


use Illuminate\Database\Eloquent\Model;

class InventarioEntrust extends Model
{
    protected $table = 'inventario2016.config_user';
    protected $primaryKey = 'id_config';
    public $timestamps = false;

    public function joinSector()
    {
        return $this->hasOne('IAServer\Http\Controllers\Inventario\Model\sector', 'id_sector', 'id_sector');
    }
    public function joinPlanta()
    {
        return $this->hasOne('IAServer\Http\Controllers\Inventario\Model\planta', 'id_planta', 'id_planta');
    }
    public function joinImpresora()
    {
        return $this->hasOne('IAServer\Http\Controllers\Inventario\Model\printer_config', 'id_printer_config', 'id_impresora');
    }

    public function joinIAServerUsers()
    {
        return $this->hasOne('IAServer\Http\Controllers\Profile\Model\printer_config', 'id_printer_config', 'id_impresora');
    }
}
