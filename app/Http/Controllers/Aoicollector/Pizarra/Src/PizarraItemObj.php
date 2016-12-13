<?php

namespace IAServer\Http\Controllers\Aoicollector\Pizarra\Src;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class PizarraItemObj extends Controller
{
    public $aoi = null;
    public $cone = null;

    public function  __construct()
    {
        $this->aoi = (object) [
            'total' => 0,
            'M' => 0,
            'T' => 0,
            'porcentaje' => 0,
            'porcentajeM' => 0,
            'porcentajeT' => 0
        ];

        $this->cone = (object) [
            'total' => 0,
            'M' => 0,
            'T' => 0,
            'porcentaje' => 0,
            'porcentajeM' => 0,
            'porcentajeT' => 0,
            'reporteIncompleto' => (object) [
                'M' => 0,
                'T' => 0
            ]
        ];
    }
}

