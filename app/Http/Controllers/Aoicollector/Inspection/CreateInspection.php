<?php

namespace IAServer\Http\Controllers\Aoicollector\Inspection;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Model\Bloque;
use IAServer\Http\Controllers\Aoicollector\Model\Panel;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CreateInspection extends Controller
{
   public function createPanel($barcode,$bloques,$op)
   {
       $insp= new Panel();

       $insp->id_maquina = 7; // Ex linea 15
       $insp->panel_barcode = $barcode;
       $insp->programa = 'AUTOMATIC';
       $insp->fecha = Carbon::now()->toDateString();
       $insp->hora = Carbon::now()->toTimeString();
       $insp->turno = 'M';
       $insp->revision_aoi = 'OK';
       $insp->revision_ins = 'OK';
       $insp->errores = 0;
       $insp->falsos = 0;
       $insp->reales = 0;
       $insp->bloques = $bloques;
       $insp->etiqueta = 'E';
       $insp->inspected_op = $op;
       $insp->created_at = Carbon::now();
       $insp->created_date = Carbon::now()->toDateString();
       $insp->created_time = Carbon::now()->toTimeString();

       $insp->save();
       $hpanel = $this->sp_insertHistoryPanel($insp->id);

       $hpanel = collect($hpanel)->first();

       return compact('insp','hpanel');
    }

    public function createBlock($insp,$barcode,$numBloque)
    {
        $insp = (object) $insp;
        $panel = $insp->insp;
        $panelHistoryId = $insp->hpanel->id;

        $bloque = new Bloque();
        $bloque->id_panel = $panel->id;
        $bloque->barcode = $barcode;
        $bloque->etiqueta = $panel->etiqueta;
        $bloque->revision_aoi = $panel->revision_aoi;
        $bloque->revision_ins = $panel->revision_ins;
        $bloque->errores = $panel->errores;
        $bloque->falsos = $panel->falsos;
        $bloque->reales = $panel->reales;
        $bloque->bloque = $numBloque;
        $bloque->save();

        $hbloque = $this->sp_insertHistoryBlock($panelHistoryId,$bloque->id);

        return $hbloque;
    }

    public function sp_insertHistoryPanel($idPanel) {
        $query = "CALL aoidata.sp_insertHistoryPanel('".$idPanel."','insert');";
        $sql = DB::connection('aoidata')->select($query);

        return $sql;
    }

    public function sp_insertHistoryBlock($idPanelHistory,$idBloque) {
        $query = "CALL aoidata.sp_insertHistoryBlock('".$idPanelHistory."','".$idBloque."');";

        dump($query);

        $sql = DB::connection('aoidata')->select($query);

        return $sql;
    }
}