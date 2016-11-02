<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use IAServer\Http\Controllers\MonitorPiso\Model\Lineas;
use Illuminate\Http\Request;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\MonitorPiso\Model\Recupero;
use Illuminate\Support\Facades\Auth;

class DBController extends Controller
{
    public function insertData($data)
    {
        $recupero = new Recupero();
        $recupero->item_id = $data->itemId;
        $recupero->part_number = $data->partNumber;
        $recupero->cantidad_recuperada = $data->cantidadRecuperada;
        $recupero->container_id = $data->containerId;
        $recupero->location_in_container = $data->locationInContainer;
        $recupero->location_in_tool = $data->locationInTool;
        $recupero->op = $data->op;
        $recupero->linea = $data->linea;
        $recupero->user = Auth::user()->name;
        $recupero->save();
    }

    public function getLineInfo($linea)
    {
        $query = Lineas::select('maquina','complex_tool')
                ->where('linea',$linea)
                ->get();
        return $query;
    }

    public function getLines()
    {
        $query = Lineas::select('linea')->get();
        return $query;
    }

    public function historialRecupero($linea,$desde,$hasta)
    {
        if ( $linea['ddLinea'] == 'todas')
        {
            $query = Recupero::select
            ('item_id',
                'part_number',
                'cantidad_recuperada',
                'container_id',
                'location_in_container',
                'op',
                'linea',
                'user',
                'created_at'
            )
                ->where('created_at','>=', $desde)
                ->where('created_at','<=',$hasta)->get();
        }
        else
        {
            $query = Recupero::select
            ('item_id',
                'part_number',
                'cantidad_recuperada',
                'container_id',
                'location_in_container',
                'op',
                'linea',
                'user',
                'created_at'
            )
                ->where('linea',$linea)
                ->where('created_at','>=', $desde)
                ->where('created_at','<=',$hasta)->get();
        }
        return $query;
    }
}
