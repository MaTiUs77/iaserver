<?php

namespace IAServer\Http\Controllers\Scrap;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\Node\RestDB2;
use IAServer\Http\Controllers\Node\RestDB2CGSDW;
use Illuminate\Http\Request;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\Cast\Object_;

class ScrapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request)
    {
        $arr = new \stdClass();
        $datePicker = Util::dateRangeFilterEsToday('pizarra_fecha');
        $arr->desde = $datePicker->desde;
        $arr->hasta = $datePicker->hasta;
        $arr->linea = $request['linea'];
        $arr->partN = $request['partN'];
        $arr->op = $request['op'];
        $arr->export = $request['export'];
        if ($arr->export === null){
            $arr->export = "false";
        }
        $items = self::getFromPicker($arr);
        $listGroup = self::createList($items);
        if ($arr->export === "false"){
            return view('scrap.reporte',["items"=>$items,"lineas"=>$listGroup[0],"ops"=>$listGroup[1],"cantMat"=>$listGroup[2],"qtyTotalMat"=>$listGroup[3]]);
        }
        else {
//            return view('scrap.reporte',["items"=>$items,"lineas"=>$listGroup[0],"ops"=>$listGroup[1]]);
            $arr->export = "false";
            return $items;
        }
    }

    public static function find($array)
    {
        $query = "SELECT distinct batch_id,
                             raw_mat_pn,
                             production_date
                      FROM cgspcm.daily_rm_consumption_all
                      WHERE production_date BETWEEN '$array->desde' AND '$array->hasta'";
        if ($array->partN !== null) {
            $query = "$query AND raw_mat_pn='$array->partN'";
        }
        $query = "$query GROUP BY batch_id,raw_mat_pn,production_date ORDER BY batch_id ASC";
        $rest = new RestDB2CGSDW();
        $resultado = $rest->get($query);
        $lista = self::createList(collect($resultado));
        return $lista;
    }


    private static function getFromPicker($array)
    {
        $npmpicker = new getNpmPicker();
        $items = $npmpicker->index($array);
//        $lista = self::createList($items);
        return $items;
    }
    private static function createList($items)
    {
        $listaLineas = array();
        $listaOP = array();
        $listGroup = array();
        $countMat=0;
        $qtyTotalMat=0;
        //Obtengo las lineas
        foreach($items as $key => $linea)
        {
            $qtyTotalMat = $qtyTotalMat + (int)$linea->total_error;
            $countMat++;
            if (!in_array($linea->id_linea,$listaLineas))
            { array_push($listaLineas,$linea->id_linea); }
        }
        $listaLineas = array_values(array_sort_recursive($listaLineas));
        //Obtengo las OP
        foreach($items as $key => $op)
        {
            $o = $op->op;
            if($o === null || $o === '') $o = 'OP_DESCONOCIDA';
            $arrOP = array(
                "linea"=>$op->id_linea,
                "op"=>$o
            );
            if (!in_array($arrOP,$listaOP))
            {
                array_push($listaOP,$arrOP);
            }
        }
        $listaOP = array_sort_recursive($listaOP);
        array_push($listGroup,$listaLineas,$listaOP,$countMat,$qtyTotalMat);
        return $listGroup;
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
    public function update(Request $request)
    {
        $linea = $request['linea'];
        dd($linea);
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
