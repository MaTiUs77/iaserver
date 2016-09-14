<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad;

use IAServer\Http\Controllers\Controller;
use IAServer\Http\Requests;

class StockerContent extends Controller
{
    public $declaracion = null;
    public $paneles = [];

    public function __construct()
    {
        $this->declaracion = new StockerContentDeclaracion();
    }

    public function process($unidades)
    {
        $this->declaracion->declarado_total = collect($this->paneles)->sum('declaracion.declarado_total');
        $this->declaracion->pendiente_total = collect($this->paneles)->sum('declaracion.pendiente_total');
        $this->declaracion->error_total = collect($this->paneles)->sum('declaracion.error_total');

        $this->declaracion->process($unidades);

        return $this;
    }
}
