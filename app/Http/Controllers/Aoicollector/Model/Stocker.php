<?php

namespace IAServer\Http\Controllers\Aoicollector\Model;

use Carbon\Carbon;
use IAServer\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Stocker extends Model
{
    protected $connection = 'aoidata';
    protected $table = 'aoidata.stocker';

    protected $fillable = ['semielaborado'];

    public function joinPanel()
    {
        return $this->hasOne('IAServer\Http\Controllers\Aoicollector\Model\Stocker', 'id', 'id_panel');
    }

    public function joinStockerTraza()
    {
        return $this->hasMany('IAServer\Http\Controllers\Aoicollector\Model\StockerTraza', 'id_stocker', 'id');
    }

    public static function vista()
    {
        $sql = self::from("aoidata.vi_stocker");
        return $sql;
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
        $sql = DB::connection('aoidata')->select($query);

        return $sql;
    }

    public static function sp_stockerReset($stocker)
    {
        $query = "CALL aoidata.sp_stockerReset('".$stocker->barcode."');";
        $sql = DB::connection('aoidata')->select($query);
        return $sql;
    }

    public static function sp_stockerAddPanel($idPanel,$idStocker, $manualMode=0)
    {
        $query = "CALL aoidata.sp_stockerAddPanel_opt('".$idPanel."','".$idStocker."', ".$manualMode.");";
        $sql = DB::connection('aoidata')->select($query);
        return $sql;
    }

    public function liberar()
    {
        return self::sp_stockerReset($this);
    }

    public function sendToRouteId($id_route)
    {
        $traza = new StockerTraza();
        $traza->id_stocker = $this->id;
        $traza->id_stocker_route = $id_route;
        $traza->created_at = Carbon::now();

        $user = Auth::user();
        if($user)
        {
            $traza->id_user = $user->id;
        }
        $traza->save();
        return $traza;
    }

    public function lavados()
    {
        return StockerTraza::where('id_stocker_route',7)
            ->where('id_stocker',$this->id);
    }

    public function inspector()
    {
        $inspector = null;

        $inspector = User::find($this->id_user);
        if ($inspector != null) {
            if ($inspector->hasProfile()) {
                $inspector->fullname = $inspector->profile->fullname();
            } else {
                $inspector->fullname = $inspector->name;
            }
        }
        return $inspector;
    }
}