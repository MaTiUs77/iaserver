<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\MonitorPiso\Model\Ins_Result;

class Quarantine extends Controller
{
    public function show()
    {
        $quarantine = $this->getQuarantine();
        $qty = $this->getQuarantineEnabled();
        return view('monitorpiso.quarantineReport',['cuarentena'=>$quarantine,'active'=>$qty]);

    }

    public static function getQuarantine()
    {
        $query = Ins_Result::select
        (
            'field1',
            'field2',
            'field4',
            'field6',
            'field7',
            'field8',
            'field9\',
            \'field10'
        )
            ->where('id_instruction','14')
            ->orderBy('field8','DESC')
            ->paginate(25);

        foreach ($query as $item)
        {
            if ($item->field7 === "0000-00-00 00:00:00"){
                $item->field7 = "";
            }
        }
        return $query;
    }

    public static function getQuarantineAsObj()
    {
        $query = Ins_Result::select
        (
            'field1',
            'field2',
            'field4',
            'field6',
            'field7',
            'field9',
            'field8',
            'field10'
        )
            ->where('id_instruction','14')
            ->orderBy('field8','DESC')
            ->get();
        foreach ($query as $item)
        {
            if ($item->field7 === "0000-00-00 00:00:00"){
                $item->field7 = "";
            }
        }
        return $query;
    }
    public static function getQuarantineEnabled()
    {
        $query = Ins_Result::select('field8')->where('field8','Y')->get();
        return $query;
    }

    public static function getFiltered($desde,$hasta,$tipoFecha)
    {
        $queryFiltered = Ins_Result::select('field1','field2','field4','field6','field7','field9','field8','field10')
            ->where('id_instruction','14')
            ->whereRaw("$tipoFecha between '$desde' and '$hasta'")
            ->orderBy('field8','DESC')
            ->paginate(25);

        foreach ($queryFiltered as $item)
        {
            if ($item->field7 === "0000-00-00 00:00:00"){
                $item->field7 = "";
            }
        }
        return $queryFiltered;
    }

    public function filter()
    {
        $filtros = Input::all();
        $desde = Carbon::createFromFormat('m/d/Y',$filtros['desde'])->format('Y-m-d 00:00:00');
        $hasta = Carbon::createFromFormat('m/d/Y',$filtros['hasta'])->format('Y-m-d 23:59:59');
        $radio = $filtros['radio'][0];

        $quarantine = $this->getFiltered($desde,$hasta,$radio);
        $qty = $this->getQuarantineEnabled();
        return view('monitorpiso.quarantineReport',['cuarentena'=>$quarantine,'active'=>$qty]);
    }
}
