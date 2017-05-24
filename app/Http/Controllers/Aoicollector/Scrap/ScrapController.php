<?php

namespace IAServer\Http\Controllers\Aoicollector\Scrap;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Panel;
use IAServer\Http\Controllers\Aoicollector\Model\PanelHistory;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Requests;

class ScrapController extends Controller
{
    public function add($barcode) {
        $find = new FindInspection();
        $find->onlyLast = true;
        $panel = (object) $find->barcode($barcode);

        $output = array();

        if(!isset($panel->error)) {
            $hpanel = $panel->last->panel;
            $hbloques = $hpanel->joinBloques;

            $panel = $hpanel->joinPanel;

            // Actualiza la inspeccion del panel e inserta un history panel
            $nhp = $this->updateInspectionPanel($panel);
            // Copia los bloques de la ultima inspeccion

        }  else  {
            $output = $panel;
        }

        return $output;
    }

    private function updateInspectionPanel(Panel $panel)
    {
        $panel->revision_ins = 'SCRAP';
        $panel->updated_at = Carbon::now();
        $panel->save();

        $hpanel = $this->sp_insertHistoryPanel($panel->id);
        $hpanel = collect($hpanel)->first();

        return compact('hpanel');
    }

    private function sp_insertHistoryPanel($idPanel) {
        $query = "CALL aoidata.sp_insertHistoryPanel('".$idPanel."','update');";
        $sql = DB::connection('aoidata')->select($query);

        return $sql;
    }
}
