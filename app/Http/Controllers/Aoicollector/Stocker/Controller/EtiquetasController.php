<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\Controller;

use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\Zebra\Zebra;
use IAServer\Http\Requests;

class EtiquetasController extends Controller
{
    public $zebra_ip = '10.30.62.120';
    public $zebra_port = 9100;
    public $zebra_prn = 'zebra/stocker';

    public function zebraPrint($stockerBarcode)
    {
        $output = "";

        $stocker = Stocker::vista()
            ->where('barcode',$stockerBarcode)
            ->first();

        if($stocker != null)
        {
            $params = [
                $stocker->barcode
            ];

            $zebra = new Zebra($this->zebra_ip, $this->zebra_port, $this->zebra_prn);
            $zebra->template($params);
            $zebra->imprimir();

            if(empty($zebra->error)) {
                $output = array(
                    "impresion"=>true,
                );
            } else {
                $output = array(
                    "impresion"=>false,
                    "error"=>$zebra->error
                );
            }
        } else
        {
            $output = array(
                "impresion"=>false,
                "error"=>"El stocker no existe"
            );
        }

        return $output;
    }
}
