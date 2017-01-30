<?php
namespace IAServer\Http\Controllers\Cogiscan;

use Carbon\Carbon;
use DebugBar\DebugBar;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class CogiscanGraficos extends Controller
{
    public function carga()
    {
        $carbonDate = Util::dateRangeFilterEs('date_session');

        $db2 = new CogiscanDB2();
        $userList = collect($db2->materialLoadedAt($carbonDate->desde,$carbonDate->hasta));
        $userListGroupByUser  = $userList->groupBy('LOAD_USER_ID');

        $byUser = [];
        foreach ($userListGroupByUser as $user => $data) {

            $agrupadoPorHora = $data->groupBy('LOAD_TMST_HORA');

            $totalPorDia = [];

            foreach($agrupadoPorHora as $fechaIndex => $fechaData)
            {
                list($fecha,$hora) = explode(' ',$fechaIndex);
                if(!isset($totalPorDia[$fecha])) {
                    $totalPorDia[$fecha] = 0;
                }
                $totalPorDia[$fecha] = count($fechaData) + $totalPorDia[$fecha];
            }

            $byUser[$user] = [
                'totalCargado' => $data->groupBy('PART_NUMBER')->count(),
                'porFecha' => $totalPorDia,
                'detalle' => $agrupadoPorHora
            ];
        }

        $byUser = collect($byUser)->sortByDesc('totalCargado');

        /*
                $userListGroupByLine  = $userList->groupBy('CURR_ITEM_ID');
                $byLine = [];
                foreach ($userListGroupByLine as $linea => $data) {

                    $agrupadoPorHora = $data->groupBy('LOAD_TMST_HORA');
                    $byLine[$linea] = [
                        'totalCargado' => $data->groupBy('PART_NUMBER')->count(),
                        'porHora' => $agrupadoPorHora
                    ];
                }*/

        $output = compact('byUser');

        return Response::multiple($output,'cogiscan.graficos.carga');
    }
}
