<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use Illuminate\Http\Request;
use IAServer\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;

class ExportToExcel extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function almacen()
    {
        Excel::create('Almacen IA', function($excel) {

            $excel->sheet('Almacen IA', function($sheet) {
                $sheet->setOrientation('landscape');
//                if (AlmacenIA::notWorking('ALMACEN'))
//                {}
//                else
//                { sleep(15); }
                $array = AlmacenIA::getAllItems();
                $sheet->fromArray($array,null,'A2',false,false);
                $sheet->row(1,array('ID','PART NUMBER','CANTIDAD','UBICACION','FECHA CREACION','FECHA ULTIMA CARGA','USUARIO DE CARGA'));
            });

        })->export('xls');
    }

    public function cuarentena()
    {
        Excel::create('Cuarentena', function($excel) {

            $excel->sheet('Cuarentena', function($sheet) {
                $sheet->setOrientation('landscape');
//                if (AlmacenIA::notWorking('CUARENTENA'))
//                {}
//                else
//                { sleep(15); }
                $arrayQ = Quarantine::getQuarantineAsObj();
                $sheet->fromArray($arrayQ,null,'A2',false,false);
                $sheet->row(1,array('ID','PART NUMBER','MOTIVO','FECHA CREACION','FECHA DESBLOQUEO','USUARIO DE CARGA','BLOQUEADO','USUARIO DESBLOQUEO'));
            });

        })->export('xls');
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
