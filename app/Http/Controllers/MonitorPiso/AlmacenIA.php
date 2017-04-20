<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use IAServer\Http\Controllers\MonitorPiso\Model\Instruction;
use IAServer\Http\Controllers\Node\RestDB2CGS;
use IAServer\Http\Controllers\MonitorPiso\Model\Ins_Result;
use IAServer\Http\Requests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Predis\Client;


class AlmacenIA extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Instruction::where('id_instruction',13)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    public function store(Request $request)
    {
        //
    }
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type='limit')
    {
        $paginar = true;
        $find = strpos($type,'field');
        if ($type === 'limit') {
            $item = $this->getItems();
            $paginar = true;
        } elseif($type ==='all') {
            $item = $this->getAllItems(false);
            $paginar = false;
        } elseif ($type ==='find'){
            $item = $this->getFiltered();
            $paginar = true;
        }
        elseif($find === false)
        {
            $item = $this->getItemsOrdered($type);
            $item->setPath(Request::url());
            /*$item->appends([
                'ordenar' => $type
            ]);*/
            $paginar = true;
        }
        $item->setPath(Request::url());
        return view('monitorpiso.containerReport',['items'=>$item,'total'=>$item->total(), 'paginar'=>$paginar]);
    }

    public static function getFiltered()
    {
        $input = Input::only('buscar');
        $pn = $input['buscar'];
        $query = "
                  SELECT ITEM_KEY,ITEM_ID,PART_NUMBER,QUANTITY,CNTR_KEY,LOCATION_IN_CNTR,INIT_TMST,LAST_LOAD_TMST,QUARANTINE_LOCKED,LOAD_USER_ID
                  FROM CGS.ITEM_INFO
                  WHERE CNTR_KEY=1330111
                  AND PART_NUMBER = '$pn'
                  ";
        $rest = new RestDB2CGS();
        $paginatedResults = $rest->paginate($query,100);
        return $paginatedResults;
    }


    public static function getItems()
    {
        $query = "
                  SELECT ITEM_KEY,ITEM_ID,PART_NUMBER,QUANTITY,CNTR_KEY,LOCATION_IN_CNTR,INIT_TMST,LAST_LOAD_TMST,QUARANTINE_LOCKED,LOAD_USER_ID
                  FROM CGS.ITEM_INFO
                  WHERE CNTR_KEY=1330111
                  ";
        $rest = new RestDB2CGS();
        $paginatedResults = $rest->paginate($query,100);
        return $paginatedResults;
    }
    public static function getItemsOrdered($type)
    {
        $query = "
                  SELECT ITEM_KEY,ITEM_ID,PART_NUMBER,QUANTITY,CNTR_KEY,LOCATION_IN_CNTR,INIT_TMST,LAST_LOAD_TMST,QUARANTINE_LOCKED,LOAD_USER_ID
                  FROM CGS.ITEM_INFO
                  WHERE CNTR_KEY=1330111
                  ORDER BY $type asc
                  ";
        $rest = new RestDB2CGS();
        $paginatedResults = $rest->paginate($query,100);
        return $paginatedResults;
    }

    public static function getAllItems($exportar)
    {
        $query = "
                  SELECT ITEM_KEY,ITEM_ID,PART_NUMBER,QUANTITY,CNTR_KEY,LOCATION_IN_CNTR,INIT_TMST,LAST_LOAD_TMST,QUARANTINE_LOCKED,LOAD_USER_ID
                  FROM CGS.ITEM_INFO
                  WHERE CNTR_KEY=1330111
                  ";
        $rest = new RestDB2CGS();
        $result = $rest->get($query);
        $total = $rest->count($query);

        if (!$exportar){
            return new LengthAwarePaginator($result,$total->TOTAL,50);
        }else{
            return $result;
        }
    }


}
