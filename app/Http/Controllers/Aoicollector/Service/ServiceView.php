<?php

namespace IAServer\Http\Controllers\Aoicollector\Service;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class ServiceView extends Service
{
    function __construct()
    {
        parent::__construct();
    }

    public function view_barcodeStatus($barcode,$withWip=false,$withDetail=false, $withProductioninfo=false, $withSmt=true)
    {
        $output = $this->barcodeStatus($barcode,$withWip,$withDetail, $withProductioninfo, $withSmt);
        return Response::multiple_output($output);
    }

    public function view_process()
    {
        return view('aoicollector.service.process.index');
    }
    public function view_process_post()
    {
        $lista = Input::get('barcodes');
        $modo= Input::get('modo');
        if(!empty($lista))
        {
            return $this->process($lista,$modo);

        } else
        {
            $output = compact('lista');
            return Response::multiple_output($output,'aoicollector.service.process.index');
        }
    }

    public function view_barcodeInBackup($barcode)
    {
        $output = $this->barcodeInBackup($barcode);
        return Response::multiple_output($output);
    }

    public function view_barcodeStatusWithWip($barcode)
    {
        return $this->view_barcodeStatus($barcode,true);
    }

    public function view_produccion($aoibarcode)
    {
        $output = Produccion::fullInfo($aoibarcode,[
            'transaction'=>false,
            'stocker'=>false,
            'smt'=>false,
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
