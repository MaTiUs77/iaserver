<?php

namespace IAServer\Http\Controllers\Scrap;

use IAServer\Http\Controllers\Scrap\Model\Stat;
use Illuminate\Http\Request;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class getNpmPicker extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($array)
    {
        $npmpicker = Stat::selectRaw(' id_linea,
                                       if(op = "" || op is null ,"OP-DESCONOCIDA",op) as "op",
                                       maquina,
                                       modulo,
                                       tabla,
                                       feeder,
                                       programa,
                                       partnumber,
                                       sum(total_error) as total_error,
                                       fecha')
            ->whereBetween('fecha',array($array->desde,$array->hasta))
            ->whereNotNull('partnumber')
            ->where('partnumber','<>','LABEL')
            ->where('partnumber','<>','ETIQUETA');
        if ($array->linea!="")
        {
            $npmpicker = $npmpicker->where('id_linea',$array->linea);
        }
        if($array->partN !="")
        {
            $npmpicker = $npmpicker->where('partnumber',$array->partN);
        }
        if($array->op != "")
        {
            $npmpicker = $npmpicker->where('op',$array->op);
        }
        return $npmpicker->groupBy('partnumber','op')->orderBy('id_linea')->get();
    }

    public function getSemiElaborate($op)
    {

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
