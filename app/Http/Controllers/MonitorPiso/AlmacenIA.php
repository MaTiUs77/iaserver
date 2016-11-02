<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use IAServer\Http\Controllers\MonitorPiso\Model\Instruction;
use Illuminate\Http\Request;
use IAServer\Http\Controllers\MonitorPiso\Model\Ins_Result;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;


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
    public function show($type='limit')
    {
        $paginar = true;
        $find = strpos($type,'field');
//        if ($this->notWorking('ALMACEN')) {
        if ($type === 'limit') {
            $item = $this->getItems();
            $total = $this->getAllItems();
            $paginar = true;
        } elseif($type ==='all') {
            $item = $this->getAllItems();
            $total = $item;
            $paginar = false;
        } elseif($find !== false)
        {
            $item = $this->getItemsOrdered($type);
            $total = $item;
            $paginar = true;
        }
        return view('monitorpiso.containerReport',['items'=>$item,'total'=>$total, 'paginar'=>$paginar]);
//        }
//        else
//        {
//            sleep(15);
//            if ($type === 'limit') {
//                $item = $this->getItems();
//                $total = $this->getAllItems();
//                $paginar = true;
//            } elseif ($type === 'all') {
//                $item = $this->getAllItems();
//                $total = $item;
//                $paginar = false;
//            }
//            return view('monitorpiso.containerReport',['items'=>$item,'total'=>$total,'paginar'=>$paginar]);
//        }
    }

    public function filtered()
    {
        if ($this->notWorking('ALMACEN')) {
            $item = $this->getFiltered();
            $total = $this->getAllItems();
        }
        else
        {
            sleep(15);
            $item = $this->getFiltered();
            $total = $this->getAllItems();
        }
        $paginar = false;
        return view('monitorpiso.containerReport',['items'=>$item,'total'=>$total, 'paginar'=>$paginar]);
    }
    public static function getFiltered()
    {
        $input = Input::only('buscar');
        $pn = $input['buscar'];
        $query = Ins_Result::select('field1','field2','field3','field5','field6','field7','field9')
            ->where('field2',$pn)
            ->orderBy('field2')
            ->paginate(100);
        return $query;
    }

    public static function notWorking($repo)
    {
        $query = Instruction::select('in_use')->where('ins_name',$repo)->get();
        if ($query[0]->in_use === "N")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getItems()
    {
        $query = Ins_Result::select('field1','field2','field3','field5','field6','field7','field9')
            ->where('id_instruction','13')
            ->orderBy('field2')
            ->paginate(100);
        return $query;
    }
    public static function getItemsOrdered($type)
    {
        $query = Ins_Result::select('field1','field2','field3','field5','field6','field7','field9')
            ->where('id_instruction','13')
            ->orderBy($type)
//            ->get();
            ->paginate(100);
        return $query;
    }

    public static function getAllItems()
    {
        $query = Ins_Result::select('field1','field2','field3','field5','field6','field7','field9')
            ->where('id_instruction','13')
            ->orderBy('field2')
            ->get();
        return $query;
    }
}
