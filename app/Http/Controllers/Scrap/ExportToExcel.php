<?php

namespace IAServer\Http\Controllers\Scrap;

use Illuminate\Http\Request;
use Carbon\Carbon;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportToExcel extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fecha = Carbon::now();
        Excel::create('Reporte de Scrap - '.$fecha, function($excel) use($request) {
        $excel->sheet('Scrap', function($sheet) use($request) {
            $sheet->setOrientation('landscape');
            $array = ScrapController::index($request);
            $newArr = $array->map(function ($item) {
                unset(
                    $item->maquina,
                    $item->modulo,
                    $item->tabla,
                    $item->feeder,
                    $item->programa,
                    $item->fecha
                );
                return $item;
            });
            $sheet->fromArray($newArr,null,'A2',false,false);
            $sheet->row(1,array('LINEA','OP','NUMERO DE PARTE','TOTAL SCRAP'));
        });})->export('xls');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
