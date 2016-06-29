<?php
namespace IAServer\Http\Controllers\Ingenieria;

use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class Listas extends Controller
{
    private $lista_ingenieria = array();
    private $tryMode = array('OLD', 'NEW', 'MEM');

    public $mode = "";
    public $posiciones = array();
    public $semielaborado = array();

    public $error = false;

    // Devuelve la lista de ingenieria
    public function readList($filePath)
    {
        $archivo = "";
        if (file_exists($filePath)) {
            $archivo = file_get_contents($filePath);
            $this->lista_ingenieria = explode("\r", $archivo);
        } else {
            $this->error = "No existe la lista de ingenieria";
        }

        return $this->lista_ingenieria;
    }

    // Localiza las posiciones de memorias
    public function findMemoryPosition()
    {
        $this->mode = array_pop($this->tryMode);
        for ($i = 1; $i < count($this->lista_ingenieria); $i++) {
            if (trim($this->lista_ingenieria[$i]) != '') {
                $linea = explode("\t", $this->lista_ingenieria[$i]);
                // Verifica si existe el indice 10(asignacion) al realizar el split Tabular
                if (array_key_exists(10, $linea)) {
                    switch ($this->mode) {
                        case 'MEM':
                            // MEM-U11
                            $this->proccessModeMEM($linea);
//                            break;
                        case 'NEW':
                            // U11-A
  //                          $this->proccessModeNEW($linea);
                            break;
                        case 'OLD':
                            // U11-G o U11-1
    //                        $this->proccessModeOLD($linea);
                            break;
                    }
                }
            }
        }

        // Si no obtuve datos reinicio y prueba con otro metodo
        if (count($this->posiciones) == 0) {
            if (count($this->tryMode) > 0) {
                $this->findMemoryPosition();
            }
        }

        // Si apesar de los intentos no se obtuvieron resultados
        if (count($this->posiciones) == 0) {
            $this->error = "No fue posible extraer datos de la lista";
        }

        return $this->posiciones;
    }
    // Metodo de extraccion de datos para listas nuevas
    // Componentes y firmwares de memoria asignados en G
    // Ej: U11-A , U11-B
    private function proccessModeNEW($linea)
    {
        $asig = $linea[10];
        $posicion = $linea[5];
        $componente = $linea[6];
        $logop = $linea[4];
        // Obtengo solo objetos asignados en Grabacion
        if ($asig == 'G') {
            list($pos, $ref) = explode('-', $posicion);
            if ($ref == "A" || $ref == "B") {
                if ($pos != "") {
                    $this->posiciones[$pos]['posicion'] = $pos;

                    // COMPONENTE Ej: U11-A
                    if ($ref == 'A') {
                        $this->posiciones[$pos]['componente'] = $componente;
                    }

                    // FIRMWARE Ej: U11-B
                    if ($ref == 'B') {
                        $this->posiciones[$pos]['firmware'] = $componente;
                    }

                    $this->proccessModeDefault($pos);
                }
            }
        }
        $this->findSemielaborado($posicion, $componente);
    }

    // Metodo de extraccion de datos para listas viejas modificadas a mano por progamadores
    // Los firmwares estan definidos en la asignacion G
    // ademas el logop tiene el prefijo MEM delante de la posicion
    // MEM-IC3000    IC3000-1     	FIRMWARE_00001    G
    // MEM-IC3000    IC3000   		COMPONENTE_001    G
    private function proccessModeMEM(array $linea)
    {
        $mem = new \stdClass();
        $mem->asignacion = $linea[10];
        $mem->posicion= $linea[5];
        $mem->componente = $linea[6];
        $mem->logop = $linea[4];
        $mem->semielaborado = $linea[0];

        if( starts_with($mem->logop,'MEM-'))
        {
            list($trash, $pos) = explode('-', $mem->logop);

            $this->posiciones[$pos]['posicion'] = $pos;
            $type1 = $pos . "-1";
            $type2 = $pos . "-G";

            if ($mem->asignacion == 'G' && ($pos == $mem->posicion)) {
                $this->posiciones[$pos]['componente'] = $mem->componente;
                $this->posiciones[$pos]['semielaborado'] = $mem->semielaborado;
            }

            if (($type1 == $mem->posicion) || ($type2 == $mem->posicion)) {
                $this->posiciones[$pos]['firmware'] = $mem->componente;
                $this->posiciones[$pos]['semielaborado'] = $mem->semielaborado;
            }

            $this->proccessModeDefault($pos);
        }

//        $this->findSemielaborado($mem->posicion, $mem->componente);
    }

    // Metodo de extraccion de datos para listas "re viejas"
    // Componentes en asignaciopn G y firmwares en asignacion S con posicion-G ej: IC3000-G
    // Ej:
    // U11-G asignacion S
    // U11 asignacion G
    private function proccessModeOLD($linea)
    {
        $asig = $linea[10];
        $posicion = $linea[5];
        $componente = $linea[6];
        $logop = $linea[4];

        // Busco componente
        if ($asig == 'G' && $posicion != '') {
            $this->posiciones[$posicion]['posicion'] = $posicion;
            $this->posiciones[$posicion]['componente'] = $componente;
            $this->proccessModeDefault($posicion);

        }
        // Busco firmware
        if ($asig == 'S') {
            list($pos, $ref) = explode('-', $posicion);
            if ($ref == "G" && $pos != '') {
                $this->posiciones[$pos]['posicion'] = $pos;
                $this->posiciones[$pos]['firmware'] = $componente;
                $this->proccessModeDefault($pos);
            }
        }

        $this->findSemielaborado($posicion, $componente);
    }

   /* // Crea una lista de los semielaborados localizados, y los guarda en un array con su posicion.
    private function findSemielaborado($posicion = "", $componente = "")
    {
        if (starts_with('4-651',$componente)) {
            $this->semielaborado[$posicion] = $componente;
        }
    }*/

    private function proccessModeDefault($pos)
    {
        if (empty($this->posiciones[$pos]['firmware'])) {
            $this->posiciones[$pos]['firmware'] = null;
        }
        if (empty($this->posiciones[$pos]['componente'])) {
            $this->posiciones[$pos]['componente'] = null;
        }
        if (empty($this->posiciones[$pos]['semielaborado'])) {
            $this->posiciones[$pos]['semielaborado'] = null;
        }
    }
}
