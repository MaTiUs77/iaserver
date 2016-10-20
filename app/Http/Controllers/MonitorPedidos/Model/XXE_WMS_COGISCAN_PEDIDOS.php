<?php

namespace IAServer\Http\Controllers\MonitorPedidos\Model;

use Illuminate\Database\Eloquent\Model;

class XXE_WMS_COGISCAN_PEDIDOS extends Model
{
    protected $connection = 'cgs_prod';
    protected $table = 'XXE_WMS_COGISCAN_PEDIDOS';
    protected $fillable = array('op_number','organization_code','operation_seq','item_code','item_uom_code','quantity','prod_line','maquina','ubicacion','status');
    public $timestamps = false;

    public function XXE_WMS_COGISCAN_PEDIDO_LPNS()
    {
        return $this->belongsToMany('XXE_WMS_COGISCAN_PEDIDO_LPNS');
    }

    public function XXE_WIP_OT()
    {
        return $this->belongsToMany('XXE_WIP_OT');
    }
    public function XXE_WMS_COGISCAN_WIP()
    {
        return $this->belongsToMany('XXE_WMS_COGISCAN_PEDIDOS');
    }
}
