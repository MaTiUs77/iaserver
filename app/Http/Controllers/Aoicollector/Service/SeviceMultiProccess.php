<?php

namespace IAServer\Http\Controllers\Aoicollector\Service;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ServiceMultiProccess extends Service
{
    function __construct()
    {
        parent::__construct();
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
}
