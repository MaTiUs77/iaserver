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
        //$userListGroupByLine = $userList->groupBy('TOP_ITEM_ID');

        $byLine = [];
        $byUser = [];

        // foreach($userListGroupByLine as $line => $datosByLine) {
            //$byLine[$line] = [];

//        dd($byLine);

            foreach ($userListGroupByUser as $user => $datosByUser) {

                $agrupadoPorHora = $datosByUser->groupBy('LOAD_TMST_HORA');

                $totalPorDia = [];

                foreach ($agrupadoPorHora as $fechaIndex => $fechaData) {
                    list($fecha, $hora) = explode(' ', $fechaIndex);
                    if (!isset($totalPorDia[$fecha])) {
                        $totalPorDia[$fecha] = 0;
                    }
                    $totalPorDia[$fecha] = count($fechaData) + $totalPorDia[$fecha];
                }

                $byUser[$user] = [
                    'totalCargado' => $datosByUser->groupBy('ITEM_ID')->count(),
                    'porFecha' => $totalPorDia,
                    'detalle' => $agrupadoPorHora,
                    'lpn' => $datosByUser->groupBy('PART_NUMBER'),
                    'lineas' => $datosByUser->groupBy('TOP_ITEM_ID')
                ];

                foreach($datosByUser->groupBy('TOP_ITEM_ID') as $line => $datosByLine) {

                    $agrupadoPorHoraByLine = $datosByLine->groupBy('LOAD_TMST_HORA');
                    $totalPorDiaByLine = [];

                    foreach ($agrupadoPorHoraByLine as $fechaIndex => $fechaData) {
                        list($fecha, $hora) = explode(' ', $fechaIndex);
                        if (!isset($totalPorDiaByLine[$fecha])) {
                            $totalPorDiaByLine[$fecha] = 0;
                        }
                        $totalPorDiaByLine[$fecha] = count($fechaData) + $totalPorDiaByLine[$fecha];
                    }
                   $byLine[$line][$user] = [
                        'totalXLinea' => $datosByUser->groupBy('ITEM_ID')->count(),
                        'totalCargado' => $datosByLine->groupBy('ITEM_ID')->count(),
                        'porFecha' => $totalPorDiaByLine,
                        'detalle' => $agrupadoPorHoraByLine,
                        'lpn' => $datosByLine->groupBy('PART_NUMBER'),
                        'lineas' => $datosByLine->groupBy('TOP_ITEM_ID')
                    ];
                }
            }

        $byLine = collect($byLine)->sortByDesc('lineas');
        $byUser = collect($byUser)->sortByDesc('totalCargado');

      return  $output = compact('byUser','byLine');

    }

    public function carga_linea()
    {
       $byLine = $this->carga();
       $line = collect($byLine['byLine']);

       $horasTotal = [];
        foreach($line as $linea => $detalle)
        {
           foreach($detalle as $nombres => $datos)
           {
               foreach($datos['detalle'] as $fechaConHora => $cargas)
               {
                   list($fecha, $hora) = explode(" ", $fechaConHora);

                   if(!in_array($hora,$horasTotal))
                   {
                       array_push($horasTotal,$hora);
                       sort($horasTotal);
                   }
               }
           }
        }
       $timeLine = $horasTotal;
       $output = compact('line','timeLine');
       return Response::multiple($output,'cogiscan.graficos.carga_linea');

    }
    public function carga_user()
    {
        $user = $this->carga();
        $byUser = collect($user['byUser']);
        $output = compact('byUser');
        return Response::multiple($output,'cogiscan.graficos.carga');

    }
}
