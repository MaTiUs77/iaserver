<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stocker extends Model
{
    protected $connection = 'iaserver';
    protected $table = 'aoidata.stocker';

    public function joinPanel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Stocker', 'id', 'id_panel');
    }

    public static function findByIdStocker($idStocker)
    {
        $sql = self::from("aoidata.vi_stocker")->where('id', $idStocker)->first();
        return $sql;
    }

    public static function findByStockerBarcode($stockerBarcode)
    {
        $sql = self::from("aoidata.vi_stocker")->where('barcode', $stockerBarcode)->first();
        return $sql;
    }

    /**
     * 	Desvincula un stocker relacionado a una AOI de produccion.
     *
     * @param $aoi_barcode
     */
    public static function changeProductionStocker($aoiBarcode,$id_stocker=null)
    {
        if(!empty($aoiBarcode))
        {
            $prod = Produccion::where('barcode',$aoiBarcode)->first();
            $prod->id_stocker = null;
            $prod->save();
        } else
        {
            // changeProductionStocker(null,4245)
            if(is_numeric($id_stocker))
            {
                $prod = Produccion::where('id_stocker',$id_stocker)->first();
                $prod->id_stocker = null;
                $prod->save();
            }
        }
    }

    public static function sp_stockerSet(Produccion $produccion, $stocker, $semielaborado)
    {
        $query = "CALL aoidata.sp_stockerSet('".$produccion->barcode."','".$stocker->barcode."','".$produccion->op."','".$stocker->limite."','".$stocker->bloques."','".$semielaborado."');";
        $sql = DB::connection('iaserver')->select($query);

        return $sql;
    }

    public static function sp_stockerReset($stocker)
    {
        $query = "CALL aoidata.sp_stockerReset('".$stocker->barcode."');";
        $sql = DB::connection('iaserver')->select($query);
        return $sql;
    }

    public static function sp_stockerAddPanel($idPanel,$idStocker, $manualMode=0)
    {
        $query = "CALL aoidata.sp_stockerAddPanel_opt('".$idPanel."','".$idStocker."', ".$manualMode.");";
        dd($query);
        $sql = DB::connection('iaserver')->select($query);
        return $sql;
    }
}
