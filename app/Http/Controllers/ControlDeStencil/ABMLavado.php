<?php

namespace IAServer\Http\Controllers\ControlDeStencil;

use Carbon\Carbon;
use IAServer\Http\Controllers\ControlDeStencil\Model\Placas;
use IAServer\Http\Controllers\IAServer\Util;
use Illuminate\Http\Request;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class ABMLavado extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $datos="";
        if ($type == 'stencil')
        {

        }
        else if ($type == 'placas')
        {
            $datos = Placas::orderBy('timestamp','desc')->take(50)->get();
        }
        return $datos;
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
    public function store($linea,$barcode)
    {
        if($barcode == "")
        {
            $barcode = "SIN-ETIQUETA";
        }
        $placas = new Placas();
        $placas->linea = $linea;
        $placas->codigo = $barcode;
        $placas->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    public static function getAll()
    {
        $linea = Input::only('linea');
        Log::debug($linea['linea']);
        $arr = new \stdClass();
        $datePicker = Util::dateRangeFilterEsToday('lavados_fecha');
        $arr->desde = Carbon::parse($datePicker->desde)->format('Y-m-d 00:00:00');
        $arr->hasta = Carbon::parse($datePicker->hasta)->format('Y-m-d 23:59:59');
        if($linea['linea'] == 'NULL' || $linea['linea'] == NULL || $linea['linea'] == "")
        {
            $placas = Placas::select('linea','codigo','responsable','timestamp')->whereBetween('timestamp',array($arr->desde,$arr->hasta))
                ->orderBy('timestamp','desc')->get();
        }
        else
        {
            $placas = Placas::select('linea','codigo','responsable','timestamp')
                ->whereBetween('timestamp',array($arr->desde,$arr->hasta))
                ->where('linea',$linea['linea'])
                ->orderBy('timestamp','desc')->get();
        }

        return $placas;
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
