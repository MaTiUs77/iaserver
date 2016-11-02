<?php

namespace IAServer\Http\Controllers\MonitorPiso;

use IAServer\Http\Controllers\Cogiscan\CogiscanDB2;
use Illuminate\Http\Request;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;


class RecuperoController extends Controller
{
    public function show()
    {
        $linea = $this->getLine();
        return view('monitorpiso.recupero',["linea"=>$linea]);
    }

    public function queryItem($itemId,$cantidad=0)
    {
        $query = new Cogiscan();
        $qi = $query->queryItem($itemId);
        $attributes = array_get($qi,'attributes');
        $rawMaterial = array_get($qi,'RawMaterial');
        $quantity = array_get($rawMaterial['attributes'],'quantity');
        $partNumber = array_get($rawMaterial['attributes'],'partNumber');
        $containerId = array_get($attributes,'containerId');
        $locationInContainer = array_get($attributes,'locationInContainer');
        $locationInTool = array_get($attributes,'locationInTool');
        $toReturn = [];
        if(empty($rawMaterial))
        { array_push($toReturn,["existe"=>false]); }
        else
        {
            if (empty($containerId))
            {
                $containerId = "N/A";
                $locationInContainer = "N/A";
                $locationInTool = "N/A";
            }
            array_push($toReturn,[
                "containerId"=>$containerId,
                "partNumber"=>$partNumber,
                "locationInContainer"=>$locationInContainer,
                "locationInTool"=>$locationInTool,
                "quantity"=>substr($quantity,0,strlen($quantity) - 2),
                "existe"=>true
            ]);
        }

        return $toReturn;
    }

    public function obtenerMaterial()
    {
        $item = Input::only('buscar');
        $qty = Input::only('qty');
        $itemId = $item['buscar'];
        $cantidad = $qty['qty'];
        $itemInfo = $this->queryItem($itemId,$cantidad);
        $arr=[];
        array_push($arr,[
            "itemId"=>$itemId,
            "partNumber"=>$itemInfo[0]['partNumber'],
            "cantidadRecuperada"=>$cantidad,
            "containerId"=>$itemInfo[0]['containerId'],
            "locationInContainer"=>$itemInfo[0]['locationInContainer'],
            "locationInTool"=>$itemInfo[0]['locationInTool'],
            "quantity"=>$itemInfo[0]['quantity'],
            "existe"=>$itemInfo[0]['existe']
        ]);
        $coleccion = collect($arr);
        $linea = $this->getLine();
        return view('monitorpiso.recupero',["resultado"=>$coleccion,"linea"=>$linea]);
    }

    public function getLine()
    {
        $path = "C:\\Recupero\\Recupero.txt";
        $contents = File::get($path);
        return $contents;
    }

    public function recuperarMaterial()
    {
        $data = Input::only('resultado');
        $cogiscan = new Cogiscan();

        //Descargo si tiene ubicacion
        $data = json_decode($data['resultado']);
        $head_data = head($data);

        $head_data->linea = $this->getLine();//agrego la línea al objeto de datos
        $head_data->op = $this->getPo($head_data->linea);//agrego la op al objeto de datos

        if ($head_data->containerId != null)
        { $cogiscan->unload($head_data->itemId,$head_data->containerId,$head_data->locationInContainer); }

        //Actualizo
        $nuevaCantidad = $head_data->cantidadRecuperada + $head_data->quantity;
        $cogiscan->updateQuantity($head_data->itemId,$nuevaCantidad);

        //Vuelvo a cargar si tenia ubicacion
        if ($head_data->containerId !=null)
        { $cogiscan->load($head_data->itemId,$head_data->containerId,$head_data->locationInContainer); }

        //Inserto en DB
        $dbController = new DBController();
        $dbController->insertData($head_data);

        return redirect('amr/recupero')->with('operacion',$head_data->itemId);
    }

    public function getPo($lineaNro)
    {
        $dbController = new DBController();
        $cogiscanDB2 = new CogiscanDB2();
        $arr = $dbController->getLineInfo($lineaNro);
        $json = json_decode($arr);
        $complex_tool = head($json)->complex_tool;
        $maquina = head($json)->maquina;
        $itemInfo = $cogiscanDB2->itemInfoByComplex($complex_tool);
        if (empty($itemInfo))
        { $itemInfo = $cogiscanDB2->itemInfoByComplex($maquina); }
        $op = collect($itemInfo)->pluck('BATCH_ID')->first();
        return $op;
    }
}
