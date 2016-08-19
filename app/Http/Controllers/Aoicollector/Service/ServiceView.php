<?php

namespace IAServer\Http\Controllers\Aoicollector\Service;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Response;

class ServiceView extends Service
{
    function __construct()
    {
        parent::__construct();
    }

    public function view_barcodeStatus($barcode,$withWip = false)
    {
        $panel = new FindInspection();
        $panel->withSmt = true;
        $panel->withCogiscan = true;
        $panel->onlyLast = true;
        $panel->withWip = $withWip;
        //$panel->withDetail = true;

        $output = $panel->barcode($barcode);

        if(isset($output->last))
        {
            $output = $output ->last;
        }

        return Response::multiple_output($output);
    }

    public function view_barcodeStatusLast($barcode)
    {
        $panel = new FindInspection();
        $panel->withSmt = true;
        $panel->withCogiscan = true;
        $panel->withWip = true;
        $panel->onlyLast = true;
        $output = $panel->barcode($barcode);

        if(isset($output ->last))
        {
            $output = $output ->last;
        }

        return Response::multiple_output($output);
    }

    public function view_barcodeStatusWithWip($barcode)
    {
        return $this->view_barcodeStatus($barcode,true);
    }


    public function view_barcodeInBackup($barcode)
    {
        $output = $this->barcodeInBackup($barcode);
        return Response::multiple_output($output);
    }

    public function view_produccion($aoibarcode)
    {
        $output = Produccion::fullInfo($aoibarcode,[
            'transaction'=>false,
            'stocker'=>false,
            'smt'=>true,
            'placas'=>false,
            'period' => false
        ]);
        return Response::multiple_output($output);
    }

    public function view_declarar($barcode)
    {
        $output = $this->declarar($barcode);
        return Response::multiple_output($output);
    }
}
