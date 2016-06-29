<?php
namespace IAServer\Http\Controllers\Ingenieria;

use IAServer\Http\Requests;

class Ingenieria extends Listas
{
      protected $listas_path = "\\\\10.30.10.12\\v1\\Users\\INSAUT\\PLANTA_3\\TECNICOS_3\\Programacion\\LISTAS\\";
//    protected $listas_path = "C:\\wamp\\www\\LISTAS\\";

    private $modelo;
    private $lote;

    public function getPositions($modelo,$lote) {
        $this->modelo = $modelo;
        $this->lote = $lote;

        // Verifica que exista la lista, y procede a cargarla en memoria.
        $filePath = $this->listas_path. $this->modelo . '\\' . $this->lote . '.txt';

        $listaIngenieria = $this->readList($filePath);

        if($this->error) {
            return $this->error;
        } else {
            return $this->findMemoryPosition();
        }

//        if($lista->error)
//        {
//            return $lista;
//        } else
//        {
//            // Busca en lista de ingenieria las memorias
//            return "Localizando memorias...";
////            return  $this->findMemoryPosition();
//        }
    }

/*
    // Localiza las posiciones de memorias
    public function findMemoryPosition(){
        $this->mode = array_pop($this->tryMode);
        for ($i = 1; $i < count($this->lista_ingenieria); $i++){
            if (trim($this->lista_ingenieria[$i]) != ''){
                $linea = explode("\t", $this->lista_ingenieria[$i]);
                // Verifica si existe el indice 10(asignacion) al realizar el split Tabular
                if (array_key_exists(10, $linea)){
                    switch($this->mode) {
                        case 'MEM':
                            // MEM-U11
                            $this->proccessModeMEM($linea);
                            break;
                        case 'NEW':
                            // U11-A
                            $this->proccessModeNEW($linea);
                            break;
                        case 'OLD':
                            // U11-G o U11-1
                            $this->proccessModeOLD($linea);
                            break;
                    }
                }
            }
        }

        // Si no obtuve datos reinicio y prueba con otro metodo
        if(count($this->posiciones)==0) {
            if(count($this->tryMode)>0) {
                $this->findMemoryPosition();
            }
        }

        return $this->posiciones;
    }

    // Metodo de extraccion de datos para listas nuevas
    // Componentes y firmwares de memoria asignados en G
    // Ej: U11-A , U11-B
    private function proccessModeNEW($linea) {
        $asig = $linea[10];
        $posicion = $linea[5];
        $componente = $linea[6];
        $logop = $linea[4];
        // Obtengo solo objetos asignados en Grabacion
        if($asig=='G') {
            list($pos,$ref) = explode('-',$posicion);
            if($ref=="A" || $ref=="B") {
                if($pos!="") {
                    $this->posiciones[$pos]['posicion'] = $pos;

                    // COMPONENTE Ej: U11-A
                    if($ref=='A') {
                        $this->posiciones[$pos]['componente'] = $componente;
                    }

                    // FIRMWARE Ej: U11-B
                    if($ref=='B') {
                        $this->posiciones[$pos]['firmware'] = $componente;
                    }

                    $this->proccessModeDefault($pos);
                }
            }
        }
        $this->findSemielaborado($posicion,$componente);
    }

    // Metodo de extraccion de datos para listas viejas modificadas a mano por progamadores
    // Los firmwares estan definidos en la asignacion G
    // ademas el logop tiene el prefijo MEM delante de la posicion
    // MEM-IC3000    IC3000-1     	FIRMWARE_00001    G
    // MEM-IC3000    IC3000   		COMPONENTE_001    G
    private function proccessModeMEM($linea) {
        $asig = $linea[10];
        $posicion = $linea[5];
        $componente = $linea[6];
        $logop = $linea[4];

        // Obtengo solo objetos asignados en Grabacion
        list($prefix,$pos) = explode('-',$logop);

        if($prefix=='MEM' && $pos!='') {
            $this->posiciones[$pos]['posicion'] = $pos;
            $type1 = $pos."-1";
            $type2 = $pos."-G";

            if($asig=='G' && ($pos == $posicion) ) {
                $this->posiciones[$pos]['componente'] = $componente;
            }

            if( ($type1 == $posicion) || ($type2 == $posicion) ) {
                $this->posiciones[$pos]['firmware'] = $componente;
            }

            $this->proccessModeDefault($pos);
        }
        $this->findSemielaborado($posicion,$componente);
    }

    // Metodo de extraccion de datos para listas "re viejas"
    // Componentes en asignaciopn G y firmwares en asignacion S con posicion-G ej: IC3000-G
    // Ej:
    // U11-G asignacion S
    // U11 asignacion G
    private function proccessModeOLD($linea) {
        $asig = $linea[10];
        $posicion = $linea[5];
        $componente = $linea[6];
        $logop = $linea[4];

        // Busco componente
        if($asig=='G' && $posicion!='' ) {
            $this->posiciones[$posicion]['posicion'] = $posicion;
            $this->posiciones[$posicion]['componente'] = $componente;
            $this->proccessModeDefault($posicion);

        }
        // Busco firmware
        if($asig=='S') {
            list($pos,$ref) = explode('-',$posicion);
            if($ref=="G" && $pos!='' ) {
                $this->posiciones[$pos]['posicion'] = $pos;
                $this->posiciones[$pos]['firmware'] = $componente;
                $this->proccessModeDefault($pos);
            }
        }

        $this->findSemielaborado($posicion,$componente);
    }

    // Crea una lista de los semielaborados localizados, y los guarda en un array con su posicion.
    private function findSemielaborado($posicion="",$componente="") {
        if(Util::startWith($componente,'4-651')) {
            $this->semielaborado[$posicion] = $componente;
        }
    }

    private function proccessModeDefault($pos) {
        if(empty($this->posiciones[$pos]['firmware'])) {
            $this->posiciones[$pos]['firmware'] = null;
        }
        if(empty($this->posiciones[$pos]['componente'])) {
            $this->posiciones[$pos]['componente'] = null;
        }
    }*/
}
