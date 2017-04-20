<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker\Panel;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle;
use IAServer\Http\Controllers\Aoicollector\Model\TransaccionWip;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\StockerController;
use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;

class PanelWithCogiscan extends StockerController
{
    public function add($rutaCogiscan,$puestoCogiscan,$panelBarcode,Produccion $produccion)
    {
        $aoibarcode = strtoupper($produccion->barcode);

        $prodinfo = (object) Produccion::fullInfo($aoibarcode,[
            'smt'=>true,
            'transaction'=>true,
            'period' => false,
            'sfcsroute' => false,
            'stocker' => false
        ]);

        $stocker = $this->stockerInfoById($produccion->id_stocker);

        $cgs = new Cogiscan();
        $queryItem = $cgs->queryItem($panelBarcode);

        $queryItemInfo = (object) $queryItem['Product']['attributes'];

        if ($stocker->op.'-BPR' == $queryItemInfo->batchId) {

            $cogiscan = $this->setRoute($rutaCogiscan,$puestoCogiscan,$panelBarcode,$produccion, $prodinfo);

            //$panelStocker = new PanelStocker($stocker);
            // $output = $panelStocker ->add($panel);

        } else {
            $error = 'La ' . $stocker->op . ' del stocker no coincide con la no ' . $queryItemInfo->batchId . ' del panel';
        }


        $output = compact('error','cogiscan');

        return $output;
    }

    private function setRoute($rutaCogiscan,$puestoCogiscan,$panelBarcode,Produccion $produccion, $prodinfo)
    {
        //**************** ALTA EN COGISCAN ****************
        $sn = [];

        if($prodinfo->produccion->route->qty_bloques>1)
        {
            for($i = 1; $i <= $prodinfo->produccion->route->qty_bloques; $i++) {
                $sn[] = "$panelBarcode-$i";
            }
        } else
        {
            $sn[] = "$panelBarcode";
        }

        $cgs = new Cogiscan();
        $cogiscan = new \stdClass();
        $cogiscan->releaseProduct = $cgs->releaseProduct(
            $prodinfo->produccion->route->cogiscan_partnumber,
            $rutaCogiscan,
            $prodinfo->produccion->op.'-BPR',
            $prodinfo->produccion->wip->wip_ot->start_quantity,
            $panelBarcode,
            $sn
        );

        $cogiscan->startOperation = $cgs->startOperation($panelBarcode,$puestoCogiscan,'');
        $cogiscan->queryItem =  $cgs->queryItem($panelBarcode);
        //************ FIN ALTA EN COGISCAN ****************

        return $cogiscan;
    }

}
