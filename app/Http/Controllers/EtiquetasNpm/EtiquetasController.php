<?php

namespace IAServer\Http\Controllers\EtiquetasNpm;

use Arcanedev\LogViewer\Bases\Controller;
use Arcanedev\Support\Collection;
use IAServer\Http\Controllers\Cogiscan\Cogiscan;
use IAServer\Http\Controllers\EtiquetasNpm\Model\etiquetas;
use IAServer\Http\Controllers\EtiquetasNpm\Model\historial_etiquetas;
use Symfony\Component\HttpFoundation\Request;

class EtiquetasController extends Controller

{
    /**
     * @param Request $serial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getUltimoId(Request $serial)
    {

        $query = new Cogiscan();

        $sn = $serial->get('sn');

        $string = substr($sn,0,-9);

        if($string == "FA0210" || $string == "FA0010")
        {
            $reimprimir = historial_etiquetas::where('feeder_sn',$sn)->get();

            if(!$reimprimir->isEmpty())
            {

                $zpl = "^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR2,2~SD25^JUS^LRN^CI0^XZ
                    ^XA
                    ^MMT
                    ^PW400
                    ^LL0136
                    ^LS0
                    ^BY3,3,33^FT86,53^BCN,,N,N
                    ^FD>:F>500".$reimprimir->first()->lado_a."^FS
                    ^BY3,3,33^FT85,119^BCN,,N,N
                    ^FD>:F>500".$reimprimir->first()->lado_b."^FS
                    ^FO10,44^GE52,53,8^FS
                    ^FT85,80^A0N,28,28^FH\^FD".$sn."^FS
                    ^PQ1,0,1,Y^XZ";

                $this->imprimir($zpl);

                return redirect('/etiquetasnpm/registrar')->with('message','Se reimprimio la etiqueta('.$sn.') - 8MM-2T');

            }
            $id = etiquetas::WHERE('feeder',8)
                ->GET();

            $ladoA = $id->first()->last_lado_a + 2;
            $ladoB = $id->first()->last_lado_b + 2;

            $zpl = "^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR2,2~SD25^JUS^LRN^CI0^XZ
                    ^XA
                    ^MMT
                    ^PW400
                    ^LL0136
                    ^LS0
                    ^BY3,3,33^FT86,53^BCN,,N,N
                    ^FD>:F>500".$ladoA."^FS
                    ^BY3,3,33^FT85,119^BCN,,N,N
                    ^FD>:F>500".$ladoB."^FS
                    ^FO10,44^GE52,53,8^FS
                    ^FT85,80^A0N,28,28^FH\^FD".$sn."^FS
                    ^PQ1,0,1,Y^XZ";

           $impresion = $this->imprimir($zpl);

            if($impresion == 1)
            {
                //SI LA IMPRESION SE CONFIRMO, ACTUALIZO LAS COLUMNAS CON LOS NUEVOS VALORES.
                $update = etiquetas::find(1);
                $update->last_lado_a = $ladoA;
                $update->last_lado_b = $ladoB;
                $update->save();

                //GUARDO EL NUMERO DE SERIE DEL FEEDER Y SUS RESPECTIVOS LADOS EN UNA BASE DE DATOS PARA LUEGO EXPORTAR EN CSV E IMPORTAR AL CONFIGURADOR DE COGISCAN
                $querySn = $query->queryItem($sn);

                $collection = Collection::make($querySn);
                $verify = $collection->pluck('itemId')->first();

                $verify2 = historial_etiquetas::where('feeder_sn',$sn)
                    ->get();



                if($verify == null)
                {

                    if(!$verify2->isEmpty())
                    {
                        return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 8MM-2T');
                    }
                    $newInsertLabel = new historial_etiquetas();
                    $newInsertLabel->feeder_sn = $sn;
                    $newInsertLabel->feeder_type = "8MM-2T";
                    $newInsertLabel->lado_a = $ladoA;
                    $newInsertLabel->lado_b = $ladoB;
                    $newInsertLabel->save();
                    return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 8MM-2T');
                }
                else{
                    return redirect('/etiquetasnpm/registrar')->with('message','Operacion Exitosa');
                }

            }
            else{
                return redirect('/etiquetasnpm/registrar')->with('message','Ocurrio un error en la impresion');
            }

        }
        elseif($string == "FA0220" || $string == "FA0020")
        {

            $parse = substr($sn,0,-13);
            $parse1 = substr($sn,2,-9);
            $parse2 = substr($sn,6,-6);
            $parse3 = substr($sn,9);

            $zpl = "^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR2,2~SD25^JUS^LRN^CI0^XZ
                    ^XA
                    ^MMT
                    ^PW400
                    ^LL0136
                    ^LS0
                    ^BY2,3,64^FT21,81^BCN,,N,N
                    ^FD>:".$parse.">5".$parse1.">6".$parse2.">5".$parse3."^FS
                    ^FT53,116^A0N,39,33^FH\^FD".$sn."^FS
                    ^PQ1,0,1,Y^XZ";

            $impresion12mm = $this->imprimir($zpl);

            if($impresion12mm == 1)
            {
                $feeder = $query->queryItem($sn);
                $collection = Collection::make($feeder);
                $verify = $collection->pluck('itemId')->first();

                $verify2 = historial_etiquetas::where('feeder_sn',$sn)
                    ->get();

                if($verify == null)
                {

                    if(!$verify2->isEmpty())
                    {
                        return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 12MM-1T');
                    }

                    $newInsertLabel = new historial_etiquetas();
                    $newInsertLabel->feeder_sn = $sn;
                    $newInsertLabel->feeder_type = "12MM-1T";
                    $newInsertLabel->lado_a = 0;
                    $newInsertLabel->lado_b = 0;
                    $newInsertLabel->save();
                    return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 12MM-1T');
                }
                else{
                    return redirect('/etiquetasnpm/registrar')->with('message','Operacion Exitosa');
                }
            }else{
                return redirect('/etiquetasnpm/registrar')->with('message','Ocurrio un error en la impresion');
            }

        }elseif($string == "FA0230" || $string == "FA0030" || $string == "FA0430")
        {
            $parse = substr($sn,0,-13);
            $parse1 = substr($sn,2,-9);
            $parse2 = substr($sn,6,-6);
            $parse3 = substr($sn,9);

            $zpl = "^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR2,2~SD25^JUS^LRN^CI0^XZ
                    ^XA
                    ^MMT
                    ^PW400
                    ^LL0136
                    ^LS0
                    ^BY2,3,64^FT21,81^BCN,,N,N
                    ^FD>:".$parse.">5".$parse1.">6".$parse2.">5".$parse3."^FS
                    ^FT53,116^A0N,39,33^FH\^FD".$sn."^FS
                    ^PQ1,0,1,Y^XZ";

            $impresion32mm = $this->imprimir($zpl);

            if($impresion32mm == 1)
            {
                $feeder = $query->queryItem($sn);
                $collection = Collection::make($feeder);
                $verify = $collection->pluck('itemId')->first();

                $verify2 = historial_etiquetas::where('feeder_sn',$sn)
                    ->get();

                if($verify == null)
                {
                    if(!$verify2->isEmpty())
                    {
                        return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 32MM-1T');
                    }

                    $newInsertLabel = new historial_etiquetas();
                    $newInsertLabel->feeder_sn = $sn;
                    $newInsertLabel->feeder_type = "32MM-1T";
                    $newInsertLabel->lado_a = 0;
                    $newInsertLabel->lado_b = 0;
                    $newInsertLabel->save();
                    return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 32MM-1T');
                }
                else{
                    return redirect('/etiquetasnpm/registrar')->with('message','Operacion Exitosa');
                }
            }else{
                return redirect('/etiquetasnpm/registrar')->with('message','Ocurrio un error en la impresion');
            }
        }elseif($string == "FA0240" || $string == "FA0440")
        {
            $parse = substr($sn,0,-13);
            $parse1 = substr($sn,2,-9);
            $parse2 = substr($sn,6,-6);
            $parse3 = substr($sn,9);

            $zpl = "^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR2,2~SD25^JUS^LRN^CI0^XZ
                    ^XA
                    ^MMT
                    ^PW400
                    ^LL0136
                    ^LS0
                    ^BY2,3,64^FT21,81^BCN,,N,N
                    ^FD>:".$parse.">5".$parse1.">6".$parse2.">5".$parse3."^FS
                    ^FT53,116^A0N,39,33^FH\^FD".$sn."^FS
                    ^PQ1,0,1,Y^XZ";

            $impresion56mm = $this->imprimir($zpl);

            if($impresion56mm == 1)
            {
                $feeder = $query->queryItem($sn);
                $collection = Collection::make($feeder);
                $verify = $collection->pluck('itemId')->first();

                $verify2 = historial_etiquetas::where('feeder_sn',$sn)
                    ->get();

                if($verify == null)
                {
                    if(!$verify2->isEmpty())
                    {
                        return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 56MM-1T');
                    }

                    $newInsertLabel = new historial_etiquetas();
                    $newInsertLabel->feeder_sn = $sn;
                    $newInsertLabel->feeder_type = "56MM-1T";
                    $newInsertLabel->lado_a = 0;
                    $newInsertLabel->lado_b = 0;
                    $newInsertLabel->save();
                    return redirect('/etiquetasnpm/registrar')->with('message','Feeder creado Exitosamente ('.$sn.') - 56MM-1T');
                }
                else{
                    return redirect('/etiquetasnpm/registrar')->with('message','Operacion Exitosa');
                }
            }else{
                return redirect('/etiquetasnpm/registrar')->with('message','Ocurrio un error en la impresion');
            }
        }
        else{

            return redirect('/etiquetasnpm/registrar')->with('message','NO SE RECONOCE EL PREFIJO DEL FEEDER');
        }


    }

    public function imprimir($zpl)
    {
        $zebra_host = '10.30.30.128';
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
}