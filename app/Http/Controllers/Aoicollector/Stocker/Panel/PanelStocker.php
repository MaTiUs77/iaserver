<?php

namespace IAServer\Http\Controllers\Aoicollector\Stocker\Panel;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Model\StockerDetalle;
use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\StockerController;
use IAServer\Http\Controllers\Redis\RedisBroadcast;
use IAServer\Http\Requests;

class PanelStocker extends StockerController
{
    public function add(PanelHistory $panel,Stocker $stocker)
    {
        $output = array();

        if($stocker->full)
        {
            $output = array('error'=>'El stocker se encuentra completo');
            Stocker::changeProductionStocker(null,$stocker->id);
        } else
        {
            if($stocker->limite==0)
            {
                // Stocker sin configuracion, establezco configuracion por defecto
                if($stocker->bloques==0)
                {
                    $updateStocker = Stocker::find($stocker->id);
                    $updateStocker->bloques = $panel->bloques;
                    $updateStocker->limite = 17;
                    $updateStocker->save();
                }
            }

            // Verifico si el panel fue asignado a algun stocker anteriormente
            $panelInStocker = StockerDetalle::where('id_panel',$panel->id)->first();
            if(isset($panelInStocker->id))
            {
                $output = array('error'=>'El panel solicitado ya fue asignado al stocker');
            } else
            {
                StockerDetalle::firstOrCreate([
                    'id_stocker' => $stocker->id,
                    'id_panel' => $panel->id
                ]);

                $stocker = Stocker::findByIdStocker($stocker->id);
                $stocker->updated_at = Carbon::now();
                $stocker->save();

                // Actualizo datos de stocker, para verificar si se encuentra full o no
                $stockerUpdatedInfo = $this->stockerInfoById($stocker->id);
                if($stockerUpdatedInfo->full)
                {
                    // Stocker completo, lo remuevo de produccion
                    Stocker::changeProductionStocker(null,$stocker->id);
                }

                $output = $stockerUpdatedInfo;

                $this->broadcast($stocker->barcode,$output);
            }
        }
        return $output;
    }

    public function remove($panelBarcode)
    {
        $find = new FindInspection();
        $panel = (object) $find->barcode($panelBarcode);

        $output = array();

        if(!isset($panel->error)) {
            $panel = $panel->last->panel;
            $stockerDetalle = StockerDetalle::where('id_panel',$panel->id)->first();
            if(isset($stockerDetalle->id))
            {
                $stockerId = $stockerDetalle->id_stocker;
                $stockerDetalle->delete();

                $stocker = Stocker::findByIdStocker($stockerId);
                $stocker->updated_at = Carbon::now();
                $stocker->save();

                $output = $this->stockerInfoById($stockerId);

                $this->broadcast($stocker->barcode,$output);
            } else
            {
                $output = array('error'=>'El panel no fue previamente agregado a un stocker');
            }
        } else
        {
            $output = $panel;
        }

        return $output;
    }
    public function broadcast($stockerBarcode,$stockerInfo)
    {
        // Guarda los datos del stocker, se mantienen por 1 semana
        $now = Carbon::now();
        $new = clone $now;
        $expire = $new->addWeek(1);
        $expireToSeg = $now->diffInSeconds($expire);

        $broadcast = new RedisBroadcast("stocker:$stockerBarcode:info");
        $broadcast->emit($stockerInfo,$expireToSeg);
    }
}
