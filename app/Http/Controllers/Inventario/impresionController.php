<?php

namespace IAServer\Http\Controllers\Inventario;

use IAServer\Http\Controllers\Inventario\Model\impresiones;
use IAServer\Http\Controllers\Inventario\Model\lpn_generator;
use IAServer\Http\Controllers\Inventario\Model\unidad_medida;
use Illuminate\Http\Request;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\Inventario\Model\materiales;
use IAServer\Http\Controllers\Inventario\Model\users;
use IAServer\Http\Controllers\Inventario\invController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class impresionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = materiales::where('codigo',$id)->take(1)->get();
        return $query;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function rePrint($id,$pn,$pconteo,$sconteo,$tconteo)
    {
        if($sconteo == 0)
        {
            $sconteo = " ";
        }
        if($tconteo == 0)
        {
            $tconteo = " ";
        }
        $user = Auth::user();
        $controller = new usersController();
        $lpngenerator = new invController();
        $lpn = $lpngenerator->findlabel($id);

        $user_info = $controller->show($user->id);
        $arrToPrint = array(
            "printer_address" => $user_info['config_user']['impresora']['printer_address'],
        );
        if ($pn == "HIBRIDO") {
            if ($user_info['config_user']['impresora']['id_printer_type'] == '203dpi') {
                $zpl = @"CT~~CD,~CC^~CT~
                                ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $user_info['config_user']['impresora']['velocidad_impresion'] . "~SD" . $user_info['config_user']['impresora']['setdarkness'] . "^JUS^LRN^CI0^XZ
                                ^XA
                                ^MMT
                                ^PW799
                                ^LL0400
                                ^LS0
                                ^FT214,187^A0N,102,96^FH\^FDHIBRIDO^FS
                                ^FT18,78^A0N,56,52^FH\^FDINVENTARIO 2016^FS
                                ^FO0,2^GB799,396,8^FS
                                ^FO494,295^GB305,103,8^FS
                                ^FO0,295^GB252,103,8^FS
                                ^FO0,197^GB799,105,8^FS
                                ^FO428,4^GB371,104,8^FS
                                ^FO0,100^GB799,105,8^FS
                                ^FO1,3^GB798,105,8^FS
                                ^BY2,3,44^FT445,95^B3N,N,,N,N
                                ^FD" . $lpn->first()->lpn . "^FS
                                ^FT16,239^A0N,34,38^FH\^FDCANTIDAD^FS
                                ^FT730,375^A0N,90,93^FH\^FD" . $lpn->first()->id_zona . "^FS
                                ^FT436,365^A0N,51,48^FH\^FD" . $lpn->first()->id_planta . "^FS
                                ^FT263,365^A0N,51,48^FH\^FDPLANTA^FS
                                ^FT515,375^A0N,85,81^FH\^FDZONA^FS
                                ^FT537,241^A0N,34,33^FH\^FD3\F8 CONTEO^FS
                                ^FT782,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT519,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT23,392^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT191,236^A0N,23,19^FH\^FDUNIDAD^FS
                                ^FT275,241^A0N,34,33^FH\^FD2\F8 CONTEO^FS
                                ^FT543,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT38,386^A0N,14,9^FH\^FD----------------------^FS
                                ^FT191,288^A0N,14,7^FH\^FD---------^FS
                                ^FT20,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT280,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT442,41^A0N,28,28^FH\^FDID:^FS
                                ^FT481,40^A0N,34,33^FH\^FD" . $lpn->first()->lpn . "^FS
                                ^LRY^FO767,205^GB0,90,23^FS^LRN
                                ^LRY^FO262,206^GB0,88,7^FS^LRN
                                ^LRY^FO503,205^GB0,89,24^FS^LRN
                                ^LRY^FO8,303^GB0,87,24^FS^LRN
                                ^LRY^FO189,211^GB69,0,32^FS^LRN
                                ^LRY^FO15,210^GB167,0,34^FS^LRN
                                ^LRY^FO17,19^GB401,0,72^FS^LRN
                                ^PQ1,0,1,Y^XZ
                                ";

                try {
                    $resultado = $this->imprimir($zpl, $arrToPrint);
                    return "Exitos";
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            } elseif ($arrToPrint['id_printer_type'] == '200dpi') {

                $zpl = @"CT~~CD,~CC^~CT~
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $user_info['config_user']['impresora']['velocidad_impresion'] . "~SD" . $user_info['config_user']['impresora']['setdarkness'] . "^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW799
                            ^LL0400
                            ^LS0
                            ^FT214,187^A0N,102,96^FH\^FDHIBRIDO^FS
                            ^FT279,372^A0N,70,67^FH\^FDINVENTARIO 2016^FS
                            ^FO0,2^GB799,396,8^FS
                            ^FO1,3^GB162,104,8^FS
                            ^FO0,295^GB252,103,8^FS
                            ^FO0,197^GB799,105,8^FS
                            ^FO428,4^GB371,104,8^FS
                            ^FO0,100^GB799,105,8^FS
                            ^FO1,3^GB798,105,8^FS
                            ^BY2,3,44^FT441,96^B3N,N,,N,N
                            ^FD" . $lpn->first()->lpn . "^FS
                            ^FT16,239^A0N,34,38^FH\^FDCANTIDAD^FS
                            ^FT340,86^A0N,85,86^FH\^FD" . $lpn->first()->id_zona . "^FS
                            ^FT52,92^A0N,51,48^FH\^FD" . $lpn->first()->id_planta . "^FS
                            ^FT14,45^A0N,42,40^FH\^FDPLANTA^FS
                            ^FT164,82^A0N,79,74^FH\^FDZONA^FS
                            ^FT537,241^A0N,34,33^FH\^FD3\F8 CONTEO^FS
                            ^FT782,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT519,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT23,392^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT191,236^A0N,23,19^FH\^FDUNIDAD^FS
                            ^FT275,241^A0N,34,33^FH\^FD2\F8 CONTEO^FS
                            ^FT543,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT38,386^A0N,14,9^FH\^FD----------------------^FS
                            ^FT191,288^A0N,14,7^FH\^FD---------^FS
                            ^FT20,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT280,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT442,41^A0N,28,28^FH\^FDID:^FS
                            ^FT481,40^A0N,34,33^FH\^FD" . $lpn->first()->lpn . "^FS
                            ^LRY^FO767,205^GB0,90,23^FS^LRN
                            ^LRY^FO262,206^GB0,88,7^FS^LRN
                            ^LRY^FO503,205^GB0,89,24^FS^LRN
                            ^LRY^FO8,303^GB0,87,24^FS^LRN
                            ^LRY^FO189,211^GB69,0,32^FS^LRN
                            ^LRY^FO15,210^GB167,0,34^FS^LRN
                            ^LRY^FO263,312^GB519,0,71^FS^LRN
                            ^PQ1,0,1,Y^XZ
                            ";

                try {
                    $resultado = $this->imprimir($zpl, $arrToPrint);
                    return "Exitos";
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            } elseif ($arrToPrint['id_printer_type'] == '300dpi') {
                $zpl = @"CT~~CD,~CC^~CT~
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $user_info['config_user']['impresora']['velocidad_impresion'] . "~SD" . $user_info['config_user']['impresora']['setdarkness'] . "^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW1248
                            ^LL0591
                            ^LS0
                            ^FT317,275^A0N,150,141^FH\^FDHIBRIDO^FS
                            ^FT412,551^A0N,104,98^FH\^FDINVENTARIO 2016^FS
                            ^FO0,4^GB1180,585,12^FS
                            ^FO1,5^GB240,153,12^FS
                            ^FO0,435^GB373,154,12^FS
                            ^FO1,292^GB1180,155,12^FS
                            ^FO632,5^GB549,155,12^FS
                            ^FO0,148^GB1181,156,12^FS
                            ^FO1,5^GB1180,155,12^FS
                            ^BY3,3,65^FT652,142^B3N,N,,N,N
                            ^FD" . $lpn->first()->lpn . "^FS
                            ^FT24,354^A0N,50,55^FH\^FDCANTIDAD^FS
                            ^FT502,126^A0N,125,127^FH\^FD" . $lpn->first()->id_zona . "^FS
                            ^FT77,135^A0N,75,72^FH\^FD" . $lpn->first()->id_planta . "^FS
                            ^FT20,67^A0N,62,60^FH\^FDPLANTA^FS
                            ^FT242,123^A0N,117,110^FH\^FDZONA^FS
                            ^FT794,357^A0N,50,48^FH\^FD3\F8 CONTEO^FS
                            ^FT1156,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                            ^FT767,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                            ^FT34,579^A0B,25,24^FH\^FDRESPONSABLE^FS
                            ^FT282,347^A0N,33,31^FH\^FDUNIDAD^FS
                            ^FT406,356^A0N,50,48^FH\^FD2\F8 CONTEO^FS
                            ^FT802,426^A0N,20,12^FH\^FD----------------------^FS
                            ^FT56,570^A0N,20,14^FH\^FD----------------------^FS
                            ^FT283,424^A0N,20,12^FH\^FD---------^FS
                            ^FT30,425^A0N,20,12^FH\^FD----------------------^FS
                            ^FT413,425^A0N,20,12^FH\^FD----------------------^FS
                            ^FT653,61^A0N,42,40^FH\^FDID:^FS
                            ^FT711,60^A0N,50,48^FH\^FD" . $lpn->first()->lpn . "^FS
                            ^LRY^FO1133,304^GB0,131,35^FS^LRN
                            ^LRY^FO388,304^GB0,131,10^FS^LRN
                            ^LRY^FO744,303^GB0,132,35^FS^LRN
                            ^LRY^FO12,447^GB0,130,35^FS^LRN
                            ^LRY^FO280,312^GB101,0,48^FS^LRN
                            ^LRY^FO22,311^GB247,0,50^FS^LRN
                            ^LRY^FO389,461^GB766,0,106^FS^LRN
                            ^PQ1,0,1,Y^XZ";

                try {
                    $resultado = $this->imprimir($zpl, $arrToPrint);
                    return "Exitos";
                } catch (Exception $e) {
                    return $e->getMessage();
                }

            } else{
                return "No existe configuracion para los dpi de la impresora";
            }
        }
        else {

            if ($user_info['config_user']['impresora']['id_printer_type'] == '203dpi' || $user_info['config_user']['impresora']['id_printer_type'] == '200dpi') {

                $print = @"CT~~CD,~CC^~CT~
                                ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $user_info['config_user']['impresora']['velocidad_impresion'] . "~SD" . $user_info['config_user']['impresora']['setdarkness'] . "^JUS^LRN^CI0^XZ
                                ^XA
                                ^MMT
                                ^PW799
                                ^LL0400
                                ^LS0
                                ^FT30,283^A0N,39,55^FH\^FD" . $pconteo . "^FS
                                ^FT41,126^A0N,23,36^FH\^FD" . $pn . " " . $lpn->first()->descripcion . "^FS
                                ^FT9,126^A0N,20,19^FH\^FDP/N^FS
                                ^FT279,372^A0N,70,67^FH\^FDINVENTARIO 2016^FS
                                ^FO0,2^GB799,396,8^FS
                                ^FO1,3^GB162,104,8^FS
                                ^FO0,295^GB252,103,8^FS
                                ^FO0,197^GB799,105,8^FS
                                ^FO428,4^GB371,104,8^FS
                                ^FO0,100^GB799,105,8^FS
                                ^FO1,3^GB798,105,8^FS
                                ^BY2,3,44^FT441,96^B3N,N,,N,N
                                ^FD" . $lpn->first()->lpn . "^FS
                                ^FT16,239^A0N,34,38^FH\^FDCANTIDAD^FS
                                ^FT340,86^A0N,85,86^FH\^FD" . $lpn->first()->id_zona . "^FS
                                ^FT52,92^A0N,51,48^FH\^FD" . $lpn->first()->id_planta . "^FS
                                ^FT14,45^A0N,42,40^FH\^FDPLANTA^FS
                                ^FT164,82^A0N,79,74^FH\^FDZONA^FS
                                ^FT537,241^A0N,34,33^FH\^FD3\F8 CONTEO^FS
                                ^FT782,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT519,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT23,392^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT196,280^A0N,31,26^FH\^FD" . $lpn->first()->unidad_medida. "^FS
                                ^FT191,236^A0N,23,19^FH\^FDUNIDAD^FS
                                ^FT275,241^A0N,34,33^FH\^FD2\F8 CONTEO^FS
                                ^FT543,288^A0N,39,55^FH\^FD". $tconteo ."^FS
                                ^FT38,386^A0N,14,9^FH\^FD----------------------^FS
                                ^FT280,288^A0N,39,55^FH\^FD". $sconteo ."^FS
                                ^FT442,41^A0N,28,28^FH\^FDID:^FS
                                ^FT481,43^A0N,37,36^FH\^FD" . $lpn->first()->lpn . "^FS
                                ^BY3,3,40^FT19,187^BCN,,N,N
                                ^FD>:" . $pn . "^FS
                                ^LRY^FO767,205^GB0,90,23^FS^LRN
                                ^LRY^FO262,206^GB0,88,7^FS^LRN
                                ^LRY^FO503,205^GB0,89,24^FS^LRN
                                ^LRY^FO8,303^GB0,87,24^FS^LRN
                                ^LRY^FO189,211^GB69,0,32^FS^LRN
                                ^LRY^FO15,210^GB167,0,34^FS^LRN
                                ^LRY^FO263,312^GB519,0,71^FS^LRN
                                ^PQ1,0,1,Y^XZ
                                ";
                try {
                    $resultado = $this->imprimir($print, $arrToPrint);
                    return "Exitos";
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            }

            elseif($user_info['config_user']['impresora']['id_printer_type'] == '300dpi')

            {
                $print = @"CT~~CD,~CC^~CT~
                        ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $user_info['config_user']['impresora']['velocidad_impresion'] . "~SD" . $user_info['config_user']['impresora']['setdarkness']  . "^JUS^LRN^CI0^XZ
                        ^XA
                        ^MMT
                        ^PW1248
                        ^LL0591
                        ^LS0
                        ^FT45,420^A0N,58,81^FH\^FD" . $pconteo . "^FS
                        ^FT61,186^A0N,33,52^FH\^FD" . $pn . " " . $lpn->first()->descripcion . "^FS
                        ^FT13,185^A0N,29,31^FH\^FDP/N^FS
                        ^FT412,551^A0N,104,98^FH\^FDINVENTARIO 2016^FS
                        ^FO0,4^GB1180,585,12^FS
                        ^FO1,5^GB240,153,12^FS
                        ^FO0,435^GB373,154,12^FS
                        ^FO1,292^GB1180,155,12^FS
                        ^FO632,5^GB549,155,12^FS
                        ^FO0,148^GB1181,156,12^FS
                        ^FO1,5^GB1180,155,12^FS
                        ^BY3,3,65^FT652,142^B3N,N,,N,N
                        ^FD".$lpn->first()->lpn."^FS
                        ^FT24,354^A0N,50,55^FH\^FDCANTIDAD^FS
                        ^FT502,126^A0N,125,127^FH\^FD" . $lpn->first()->id_zona . "^FS
                        ^FT77,135^A0N,75,72^FH\^FD" . $lpn->first()->id_planta . "^FS
                        ^FT20,67^A0N,62,60^FH\^FDPLANTA^FS
                        ^FT242,123^A0N,117,110^FH\^FDZONA^FS
                        ^FT794,357^A0N,50,48^FH\^FD3\F8 CONTEO^FS
                        ^FT1156,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                        ^FT767,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                        ^FT34,579^A0B,25,24^FH\^FDRESPONSABLE^FS
                        ^FT290,415^A0N,46,38^FH\^FD". $lpn->first()->unidad_medida ."^FS
                        ^FT282,347^A0N,33,31^FH\^FDUNIDAD^FS
                        ^FT406,356^A0N,50,48^FH\^FD2\F8 CONTEO^FS
                        ^FT802,426^A0N,58,81^FH\^FD". $tconteo ."^FS
                        ^FT56,570^A0N,20,14^FH\^FD----------------------^FS
                        ^FT413,425^A0N,58,81^FH\^FD".$sconteo."^FS
                        ^FT653,61^A0N,42,40^FH\^FDID:^FS
                        ^FT711,64^A0N,54,52^FH\^FD".$lpn->first()->lpn."^FS
                        ^BY3,3,59^FT28,277^BCN,,N,N
                        ^FD>:" . $pn . "^FS
                        ^LRY^FO1133,304^GB0,131,35^FS^LRN
                        ^LRY^FO388,304^GB0,131,10^FS^LRN
                        ^LRY^FO744,303^GB0,132,35^FS^LRN
                        ^LRY^FO12,447^GB0,130,35^FS^LRN
                        ^LRY^FO280,312^GB101,0,48^FS^LRN
                        ^LRY^FO22,311^GB247,0,50^FS^LRN
                        ^LRY^FO389,461^GB766,0,106^FS^LRN
                        ^PQ1,0,1,Y^XZ";
                try {
                    $resultado = $this->imprimir($print, $arrToPrint);
                    return "Exitos";
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            } else
            {
                return "NO EXISTE UNA CONFIGURACION PARA ESTE TIPO DE IMPRESORA.";
            }
        }
    }

    public function toPrint(Request $partnumber)
    {

        if($partnumber->get('pn') == 'HIBRIDO') {

            $insert = new lpn_generator();
            $USER = Auth::user();
            $getuser = new usersController();
            $user_info = $getuser->show($USER->id);
           // $user_info = invController::userInfo($USER->id)
            $arrToPrint = array(
                "userName" => $USER->name,
                "id_sector" => $user_info['config_user']['id_sector'],
                "id_planta" => $user_info['config_user']['id_planta'],
                "id_impresora" => $user_info['config_user']['impresora']['id_impresora'],
                "printer_address" => $user_info['config_user']['impresora']['printer_address'],
                "id_printer_type" => $user_info['config_user']['impresora']['id_printer_type'],
                "setdarkness" => $user_info['config_user']['impresora']['setdarkness'],
                "velocidad_impresion" => $user_info['config_user']['impresora']['velocidad_impresion'],
            );

            $lpn = lpn_generator::where('id', '>', 0)->orderby('id', 'DESC')->first();

            $insert->pn = "HIBRIDO";
            $insert->lpn = $lpn->lpn + 1;
            $insert->cant = "0";
            $insert->planta = $arrToPrint['id_planta'];
            $insert->zona = $arrToPrint['id_sector'];
            $insert->user = $arrToPrint['userName'];
            $insert->save();

            if ($arrToPrint['id_printer_type'] == '203dpi'){
                $zpl = @"CT~~CD,~CC^~CT~
                                ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $arrToPrint['velocidad_impresion'] . "~SD" . $arrToPrint['setdarkness'] . "^JUS^LRN^CI0^XZ
                                ^XA
                                ^MMT
                                ^PW799
                                ^LL0400
                                ^LS0
                                ^FT214,187^A0N,102,96^FH\^FDHIBRIDO^FS
                                ^FT18,78^A0N,56,52^FH\^FDINVENTARIO 2016^FS
                                ^FO0,2^GB799,396,8^FS
                                ^FO494,295^GB305,103,8^FS
                                ^FO0,295^GB252,103,8^FS
                                ^FO0,197^GB799,105,8^FS
                                ^FO428,4^GB371,104,8^FS
                                ^FO0,100^GB799,105,8^FS
                                ^FO1,3^GB798,105,8^FS
                                ^BY2,3,44^FT445,95^B3N,N,,N,N
                                ^FD" . $insert->lpn . "^FS
                                ^FT16,239^A0N,34,38^FH\^FDCANTIDAD^FS
                                ^FT730,375^A0N,90,93^FH\^FD" . $arrToPrint['id_sector'] . "^FS
                                ^FT436,365^A0N,51,48^FH\^FD" . $arrToPrint['id_planta'] . "^FS
                                ^FT263,365^A0N,51,48^FH\^FDPLANTA^FS
                                ^FT515,375^A0N,85,81^FH\^FDZONA^FS
                                ^FT537,241^A0N,34,33^FH\^FD3\F8 CONTEO^FS
                                ^FT782,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT519,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT23,392^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT191,236^A0N,23,19^FH\^FDUNIDAD^FS
                                ^FT275,241^A0N,34,33^FH\^FD2\F8 CONTEO^FS
                                ^FT543,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT38,386^A0N,14,9^FH\^FD----------------------^FS
                                ^FT191,288^A0N,14,7^FH\^FD---------^FS
                                ^FT20,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT280,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT442,41^A0N,28,28^FH\^FDID:^FS
                                ^FT481,40^A0N,34,33^FH\^FD" . $insert->lpn . "^FS
                                ^LRY^FO767,205^GB0,90,23^FS^LRN
                                ^LRY^FO262,206^GB0,88,7^FS^LRN
                                ^LRY^FO503,205^GB0,89,24^FS^LRN
                                ^LRY^FO8,303^GB0,87,24^FS^LRN
                                ^LRY^FO189,211^GB69,0,32^FS^LRN
                                ^LRY^FO15,210^GB167,0,34^FS^LRN
                                ^LRY^FO17,19^GB401,0,72^FS^LRN
                                ^PQ1,0,1,Y^XZ
                                ";

                $resultado = $this->imprimir($zpl,$arrToPrint);

            if($resultado == 1)
            {
                $impresion = new impresiones();
                $impresion->id_etiqueta = $insert->id;
                $impresion->id_responsable_imp = $arrToPrint['userName'];
                $impresion->id_partnumber = "HIBRIDO";
                $impresion->id_zona = $arrToPrint['id_sector'];
                $impresion->cant_agregada = "0";
                $impresion->seg_conteo = "0";
                $impresion->ter_conteo = "0";
                $impresion->id_planta = $arrToPrint['id_planta'];
                $impresion->save();
                return redirect('/inventario/imprimir')->with('message','Operación Exitosa');
            }
        }
            elseif($arrToPrint['id_printer_type'] == '200dpi')
            {

                $zpl = @"CT~~CD,~CC^~CT~
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $arrToPrint['velocidad_impresion'] . "~SD" . $arrToPrint['setdarkness'] . "^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW799
                            ^LL0400
                            ^LS0
                            ^FT214,187^A0N,102,96^FH\^FDHIBRIDO^FS
                            ^FT279,372^A0N,70,67^FH\^FDINVENTARIO 2016^FS
                            ^FO0,2^GB799,396,8^FS
                            ^FO1,3^GB162,104,8^FS
                            ^FO0,295^GB252,103,8^FS
                            ^FO0,197^GB799,105,8^FS
                            ^FO428,4^GB371,104,8^FS
                            ^FO0,100^GB799,105,8^FS
                            ^FO1,3^GB798,105,8^FS
                            ^BY2,3,44^FT441,96^B3N,N,,N,N
                            ^FD" . $insert->lpn . "^FS
                            ^FT16,239^A0N,34,38^FH\^FDCANTIDAD^FS
                            ^FT340,86^A0N,85,86^FH\^FD" . $arrToPrint['id_sector'] . "^FS
                            ^FT52,92^A0N,51,48^FH\^FD" . $arrToPrint['id_planta'] . "^FS
                            ^FT14,45^A0N,42,40^FH\^FDPLANTA^FS
                            ^FT164,82^A0N,79,74^FH\^FDZONA^FS
                            ^FT537,241^A0N,34,33^FH\^FD3\F8 CONTEO^FS
                            ^FT782,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT519,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT23,392^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT191,236^A0N,23,19^FH\^FDUNIDAD^FS
                            ^FT275,241^A0N,34,33^FH\^FD2\F8 CONTEO^FS
                            ^FT543,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT38,386^A0N,14,9^FH\^FD----------------------^FS
                            ^FT191,288^A0N,14,7^FH\^FD---------^FS
                            ^FT20,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT280,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT442,41^A0N,28,28^FH\^FDID:^FS
                            ^FT481,40^A0N,34,33^FH\^FD" . $insert->lpn . "^FS
                            ^LRY^FO767,205^GB0,90,23^FS^LRN
                            ^LRY^FO262,206^GB0,88,7^FS^LRN
                            ^LRY^FO503,205^GB0,89,24^FS^LRN
                            ^LRY^FO8,303^GB0,87,24^FS^LRN
                            ^LRY^FO189,211^GB69,0,32^FS^LRN
                            ^LRY^FO15,210^GB167,0,34^FS^LRN
                            ^LRY^FO263,312^GB519,0,71^FS^LRN
                            ^PQ1,0,1,Y^XZ
                            ";

                $resultado = $this->imprimir($zpl,$arrToPrint);

                if($resultado == 1)
                {
                    $impresion = new impresiones();
                    $impresion->id_etiqueta = $insert->id;
                    $impresion->id_responsable_imp = $arrToPrint['userName'];
                    $impresion->id_partnumber = "HIBRIDO";
                    $impresion->id_zona = $arrToPrint['id_sector'];
                    $impresion->cant_agregada = "0";
                    $impresion->seg_conteo = "0";
                    $impresion->ter_conteo = "0";
                    $impresion->id_planta = $arrToPrint['id_planta'];
                    $impresion->save();
                    return redirect('/inventario/imprimir')->with('message','Operación Exitosa');
                }
            }
            elseif($arrToPrint['id_printer_type'] == '300dpi')
            {
                $zpl = @"CT~~CD,~CC^~CT~
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $arrToPrint['velocidad_impresion'] . "~SD" . $arrToPrint['setdarkness'] . "^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW1248
                            ^LL0591
                            ^LS0
                            ^FT317,275^A0N,150,141^FH\^FDHIBRIDO^FS
                            ^FT412,551^A0N,104,98^FH\^FDINVENTARIO 2016^FS
                            ^FO0,4^GB1180,585,12^FS
                            ^FO1,5^GB240,153,12^FS
                            ^FO0,435^GB373,154,12^FS
                            ^FO1,292^GB1180,155,12^FS
                            ^FO632,5^GB549,155,12^FS
                            ^FO0,148^GB1181,156,12^FS
                            ^FO1,5^GB1180,155,12^FS
                            ^BY3,3,65^FT652,142^B3N,N,,N,N
                            ^FD" . $insert->lpn . "^FS
                            ^FT24,354^A0N,50,55^FH\^FDCANTIDAD^FS
                            ^FT502,126^A0N,125,127^FH\^FD" . $arrToPrint['id_sector'] . "^FS
                            ^FT77,135^A0N,75,72^FH\^FD" . $arrToPrint['id_planta'] . "^FS
                            ^FT20,67^A0N,62,60^FH\^FDPLANTA^FS
                            ^FT242,123^A0N,117,110^FH\^FDZONA^FS
                            ^FT794,357^A0N,50,48^FH\^FD3\F8 CONTEO^FS
                            ^FT1156,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                            ^FT767,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                            ^FT34,579^A0B,25,24^FH\^FDRESPONSABLE^FS
                            ^FT282,347^A0N,33,31^FH\^FDUNIDAD^FS
                            ^FT406,356^A0N,50,48^FH\^FD2\F8 CONTEO^FS
                            ^FT802,426^A0N,20,12^FH\^FD----------------------^FS
                            ^FT56,570^A0N,20,14^FH\^FD----------------------^FS
                            ^FT283,424^A0N,20,12^FH\^FD---------^FS
                            ^FT30,425^A0N,20,12^FH\^FD----------------------^FS
                            ^FT413,425^A0N,20,12^FH\^FD----------------------^FS
                            ^FT653,61^A0N,42,40^FH\^FDID:^FS
                            ^FT711,60^A0N,50,48^FH\^FD" . $insert->lpn . "^FS
                            ^LRY^FO1133,304^GB0,131,35^FS^LRN
                            ^LRY^FO388,304^GB0,131,10^FS^LRN
                            ^LRY^FO744,303^GB0,132,35^FS^LRN
                            ^LRY^FO12,447^GB0,130,35^FS^LRN
                            ^LRY^FO280,312^GB101,0,48^FS^LRN
                            ^LRY^FO22,311^GB247,0,50^FS^LRN
                            ^LRY^FO389,461^GB766,0,106^FS^LRN
                            ^PQ1,0,1,Y^XZ";

                $resultado = $this->imprimir($zpl,$arrToPrint);

                if($resultado == 1)
                {
                    $impresion = new impresiones();
                    $impresion->id_etiqueta = $insert->id;
                    $impresion->id_responsable_imp = $arrToPrint['userName'];
                    $impresion->id_partnumber = "HIBRIDO";
                    $impresion->id_zona = $arrToPrint['id_sector'];
                    $impresion->cant_agregada = "0";
                    $impresion->seg_conteo = "0";
                    $impresion->ter_conteo = "0";
                    $impresion->id_planta = $arrToPrint['id_planta'];
                    $impresion->save();
                    return redirect('/inventario/imprimir')->with('message','Operación Exitosa');
                }
            }
        }
        else{

            $PN = $partnumber->get('pn');
            $QTY = $partnumber->get('qty');
            $USER = Auth::user();
            $PNinfo = $this->show($PN);
            $infousuario = new usersController();
            $user_info = $infousuario->show($USER->id);
            
            $arrToPrint = array(
                "partNumber" => $PN,
                "quantity" => $QTY,
                "DescPartNumber" => $PNinfo[0]['descripcion'],
                "Unidad_medida" => $PNinfo[0]['unidad_medida'],
                "userName" => $USER->name,
                "userPass" => $USER->password,
                //"userDesc" => $user_info[0]['descripcion'],
                "id_sector" => $user_info['config_user']['id_sector'],
                "id_planta" => $user_info['config_user']['id_planta'],
                "id_impresora" => $user_info['config_user']['impresora']['id_impresora'],
                "printer_address" => $user_info['config_user']['impresora']['printer_address'],
                "id_printer_type" => $user_info['config_user']['impresora']['id_printer_type'],
                "setdarkness" => $user_info['config_user']['impresora']['setdarkness'],
                "velocidad_impresion" => $user_info['config_user']['impresora']['velocidad_impresion'],
            );

            //enviar el array a una funcion para generar el prn
            $this->prnPrint($arrToPrint);

            return redirect('inventario/imprimir')->with('message','Operacion Exitosa');
        }

    }

    public function prnPrint($array)
    {
                $insert = new lpn_generator();

                $lpn = lpn_generator::where('id', '>', 0)->orderby('id', 'DESC')->first();

                    $insert->pn = $array['partNumber'];
                    $insert->lpn = $lpn->lpn+1;
                    $insert->cant = $array['quantity'];
                    $insert->planta = $array['id_planta'];
                    $insert->zona = $array['id_sector'];
                    $insert->user = $array['userName'];
                    $insert->save();

        if($array['id_printer_type'] == '200dpi') {
                    $print = @"CT~~CD,~CC^~CT~
                                ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $array['velocidad_impresion'] . "~SD" . $array['setdarkness'] . "^JUS^LRN^CI0^XZ
                                ^XA
                                ^MMT
                                ^PW799
                                ^LL0400
                                ^LS0
                                ^FT30,283^A0N,39,55^FH\^FD" . $array['quantity'] . "^FS
                                ^FT41,126^A0N,23,36^FH\^FD" . $array['partNumber'] . " " . $array['DescPartNumber'] . "^FS
                                ^FT9,126^A0N,20,19^FH\^FDP/N^FS
                                ^FT279,372^A0N,70,67^FH\^FDINVENTARIO 2016^FS
                                ^FO0,2^GB799,396,8^FS
                                ^FO1,3^GB162,104,8^FS
                                ^FO0,295^GB252,103,8^FS
                                ^FO0,197^GB799,105,8^FS
                                ^FO428,4^GB371,104,8^FS
                                ^FO0,100^GB799,105,8^FS
                                ^FO1,3^GB798,105,8^FS
                                ^BY2,3,44^FT441,96^B3N,N,,N,N
                                ^FD".$insert->lpn."^FS
                                ^FT16,239^A0N,34,38^FH\^FDCANTIDAD^FS
                                ^FT340,86^A0N,85,86^FH\^FD" . $array['id_sector'] . "^FS
                                ^FT52,92^A0N,51,48^FH\^FD" . $array['id_planta'] . "^FS
                                ^FT14,45^A0N,42,40^FH\^FDPLANTA^FS
                                ^FT164,82^A0N,79,74^FH\^FDZONA^FS
                                ^FT537,241^A0N,34,33^FH\^FD3\F8 CONTEO^FS
                                ^FT782,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT519,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT23,392^A0B,17,14^FH\^FDRESPONSABLE^FS
                                ^FT196,280^A0N,31,26^FH\^FD".$array['Unidad_medida']."^FS
                                ^FT191,236^A0N,23,19^FH\^FDUNIDAD^FS
                                ^FT275,241^A0N,34,33^FH\^FD2\F8 CONTEO^FS
                                ^FT543,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT38,386^A0N,14,9^FH\^FD----------------------^FS
                                ^FT280,288^A0N,14,7^FH\^FD----------------------^FS
                                ^FT442,41^A0N,28,28^FH\^FDID:^FS
                                ^FT481,43^A0N,37,36^FH\^FD".$insert->lpn."^FS
                                ^BY3,3,40^FT19,187^BCN,,N,N
                                ^FD>:" . $array['partNumber'] . "^FS
                                ^LRY^FO767,205^GB0,90,23^FS^LRN
                                ^LRY^FO262,206^GB0,88,7^FS^LRN
                                ^LRY^FO503,205^GB0,89,24^FS^LRN
                                ^LRY^FO8,303^GB0,87,24^FS^LRN
                                ^LRY^FO189,211^GB69,0,32^FS^LRN
                                ^LRY^FO15,210^GB167,0,34^FS^LRN
                                ^LRY^FO263,312^GB519,0,71^FS^LRN
                                ^PQ1,0,1,Y^XZ
                                ";

                     $okOrNg = $this->imprimir($print,$array);

                if($okOrNg == 1)
                {
                    $impresion = new impresiones();
                    $impresion->id_etiqueta = $insert->id;
                    $impresion->id_responsable_imp = $array['userName'];
                    $impresion->id_partnumber = $array['partNumber'];
                    $impresion->id_zona = $array['id_sector'];
                    $impresion->cant_agregada = $array['quantity'];
                    $impresion->seg_conteo = "0";
                    $impresion->ter_conteo = "0";
                    $impresion->id_planta = $array['id_planta'];
                    $impresion->save();
                }
            }
        elseif($array['id_printer_type']=='203dpi')
        {

            $print = @"CT~~CD,~CC^~CT~
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $array['velocidad_impresion'] . "~SD" . $array['setdarkness'] . "^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW799
                            ^LL0400
                            ^LS0
                            ^FT30,283^A0N,39,55^FH\^FD" . $array['quantity'] . "^FS
                            ^FT41,126^A0N,23,36^FH\^FD" . $array['partNumber'] . " " . $array['DescPartNumber'] . "^FS
                            ^FT9,126^A0N,20,19^FH\^FDP/N^FS
                            ^FT279,372^A0N,70,67^FH\^FDINVENTARIO 2016^FS
                            ^FO0,2^GB799,396,8^FS
                            ^FO1,3^GB162,104,8^FS
                            ^FO0,295^GB252,103,8^FS
                            ^FO0,197^GB799,105,8^FS
                            ^FO428,4^GB371,104,8^FS
                            ^FO0,100^GB799,105,8^FS
                            ^FO1,3^GB798,105,8^FS
                            ^BY2,3,44^FT441,96^B3N,N,,N,N
                            ^FD".$insert->lpn."^FS
                            ^FT16,239^A0N,34,38^FH\^FDCANTIDAD^FS
                            ^FT340,86^A0N,85,86^FH\^FD".$array['id_sector']."^FS
                            ^FT52,92^A0N,51,48^FH\^FD".$array['id_planta']."^FS
                            ^FT14,45^A0N,42,40^FH\^FDPLANTA^FS
                            ^FT164,82^A0N,79,74^FH\^FDZONA^FS
                            ^FT537,241^A0N,34,33^FH\^FD3\F8 CONTEO^FS
                            ^FT782,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT519,294^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT23,392^A0B,17,14^FH\^FDRESPONSABLE^FS
                            ^FT196,280^A0N,31,26^FH\^FD".$array['Unidad_medida']."^FS
                            ^FT191,236^A0N,23,19^FH\^FDUNIDAD^FS
                            ^FT275,241^A0N,34,33^FH\^FD2\F8 CONTEO^FS
                            ^FT543,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT38,386^A0N,14,9^FH\^FD----------------------^FS
                            ^FT280,288^A0N,14,7^FH\^FD----------------------^FS
                            ^FT442,41^A0N,28,28^FH\^FDID:^FS
                            ^FT481,43^A0N,37,36^FH\^FD".$insert->lpn."^FS
                            ^BY2,3,40^FT19,187^BCN,,N,N
                            ^FD>:" . $array['partNumber'] . "^FS
                            ^LRY^FO767,205^GB0,90,23^FS^LRN
                            ^LRY^FO262,206^GB0,88,7^FS^LRN
                            ^LRY^FO503,205^GB0,89,24^FS^LRN
                            ^LRY^FO8,303^GB0,87,24^FS^LRN
                            ^LRY^FO189,211^GB69,0,32^FS^LRN
                            ^LRY^FO15,210^GB167,0,34^FS^LRN
                            ^LRY^FO263,312^GB519,0,71^FS^LRN
                            ^PQ1,0,1,Y^XZ
                            ";

                     $okOrNg = $this->imprimir($print,$array);

            if($okOrNg == 1)
            {
                $impresion = new impresiones();
                $impresion->id_etiqueta = $insert->id;
                $impresion->id_responsable_imp = $array['userName'];
                $impresion->id_partnumber = $array['partNumber'];
                $impresion->id_zona = $array['id_sector'];
                $impresion->cant_agregada = $array['quantity'];
                $impresion->seg_conteo = "0";
                $impresion->ter_conteo = "0";
                $impresion->id_planta = $array['id_planta'];
                $impresion->save();

            }
        }
        elseif($array['id_printer_type'] == '300dpi')
        {
            $print = @"CT~~CD,~CC^~CT~
                        ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR" . $array['velocidad_impresion'] . "~SD" . $array['setdarkness'] . "^JUS^LRN^CI0^XZ
                        ^XA
                        ^MMT
                        ^PW1248
                        ^LL0591
                        ^LS0
                        ^FT45,420^A0N,58,81^FH\^FD" . $array['quantity'] . "^FS
                        ^FT61,186^A0N,33,52^FH\^FD" . $array['partNumber'] . " " . $array['DescPartNumber'] . "^FS
                        ^FT13,185^A0N,29,31^FH\^FDP/N^FS
                        ^FT412,551^A0N,104,98^FH\^FDINVENTARIO 2016^FS
                        ^FO0,4^GB1180,585,12^FS
                        ^FO1,5^GB240,153,12^FS
                        ^FO0,435^GB373,154,12^FS
                        ^FO1,292^GB1180,155,12^FS
                        ^FO632,5^GB549,155,12^FS
                        ^FO0,148^GB1181,156,12^FS
                        ^FO1,5^GB1180,155,12^FS
                        ^BY3,3,65^FT652,142^B3N,N,,N,N
                        ^FD".$insert->lpn."^FS
                        ^FT24,354^A0N,50,55^FH\^FDCANTIDAD^FS
                        ^FT502,126^A0N,125,127^FH\^FD" . $array['id_sector'] . "^FS
                        ^FT77,135^A0N,75,72^FH\^FD" . $array['id_planta'] . "^FS
                        ^FT20,67^A0N,62,60^FH\^FDPLANTA^FS
                        ^FT242,123^A0N,117,110^FH\^FDZONA^FS
                        ^FT794,357^A0N,50,48^FH\^FD3\F8 CONTEO^FS
                        ^FT1156,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                        ^FT767,435^A0B,25,24^FH\^FDRESPONSABLE^FS
                        ^FT34,579^A0B,25,24^FH\^FDRESPONSABLE^FS
                        ^FT290,415^A0N,46,38^FH\^FD".$array['Unidad_medida']."^FS
                        ^FT282,347^A0N,33,31^FH\^FDUNIDAD^FS
                        ^FT406,356^A0N,50,48^FH\^FD2\F8 CONTEO^FS
                        ^FT802,426^A0N,20,12^FH\^FD----------------------^FS
                        ^FT56,570^A0N,20,14^FH\^FD----------------------^FS
                        ^FT413,425^A0N,20,12^FH\^FD----------------------^FS
                        ^FT653,61^A0N,42,40^FH\^FDID:^FS
                        ^FT711,64^A0N,54,52^FH\^FD".$insert->lpn."^FS
                        ^BY3,3,59^FT28,277^BCN,,N,N
                        ^FD>:" . $array['partNumber'] . "^FS
                        ^LRY^FO1133,304^GB0,131,35^FS^LRN
                        ^LRY^FO388,304^GB0,131,10^FS^LRN
                        ^LRY^FO744,303^GB0,132,35^FS^LRN
                        ^LRY^FO12,447^GB0,130,35^FS^LRN
                        ^LRY^FO280,312^GB101,0,48^FS^LRN
                        ^LRY^FO22,311^GB247,0,50^FS^LRN
                        ^LRY^FO389,461^GB766,0,106^FS^LRN
                        ^PQ1,0,1,Y^XZ";

            $okOrNg = $this->imprimir($print,$array);

            if($okOrNg == 1)
            {
                $impresion = new impresiones();
                $impresion->id_etiqueta = $insert->id;
                $impresion->id_responsable_imp = $array['userName'];
                $impresion->id_partnumber = $array['partNumber'];
                $impresion->id_zona = $array['id_sector'];
                $impresion->cant_agregada = $array['quantity'];
                $impresion->seg_conteo = "0";
                $impresion->ter_conteo = "0";
                $impresion->id_planta = $array['id_planta'];
                $impresion->save();
            }
        }
    }

    public function imprimir($zpl,$array)
    {
//        $zebra_host = '10.30.30.128';
        $zebra_host = $array['printer_address'];
        $zebra_port = '9100';

        try {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket === false) {
                echo "socket_create() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
                return false;
            }

            $result = socket_connect($socket, $zebra_host, $zebra_port);
            if ($result === false) {
                echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
                return false;
            }

            socket_write($socket, $zpl, strlen($zpl));
            socket_close($socket);
            return 1;

        }
        catch (Exception $e) {

            echo $e->getMessage();
        }
    }
    public function getUnit()
    {
        return unidad_medida::all();
    }
    public function insertMaterial(Request $datos)
    {
        $um = unidad_medida::where('descripcion',$datos->get('udm'));

        $newinsert = new materiales();
        $newinsert->codigo = $datos->get('partnumber');
        $newinsert->descripcion = $datos->get('descripcion');
        $newinsert->unidad_medida = $datos->get('udm');
        $newinsert->desc_u_medida = $um->first()->u_medida;
        $newinsert->save();

        return redirect('/inventario/imprimir')->with('message','Material agregado correctamente');
    }
    public function getUserInfo($user)
    {
        return users::where('user_id',$user)->get();
    }
}
