<?php
namespace IAServer\Http\Controllers\Zebra;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
 * Impresion con Zebra
 *
 * @package IAServer\Http\Controllers\Zebra
 */
class Zebra extends Controller
{
    public $host = "";
    public $port = "";
    public $error = "";
    public $prn = "";

    /**
     * Zebra constructor.
     *
     * @param string $host Host de impresora
     * @param int $port Puerto de impresora
     * @param string $prnFile Ruta del archivo .prn guardado en storage/app/
     *
     * @example php
     * <php>
     *      $zebra = new Zebra('ARUSHAP34',5900,'zebra/memorias');
     *      $zebra->template(['Dato1','Dato2','Dato3']);
     *      $zebra->imprimir();
     * </php>
     */
    public function __construct($host,$port,$prnFile) {
        $this->host = $host;
        $this->port = $port;
        $prnFile = $prnFile.".prn";

        if(Storage::exists($prnFile)) {
            $this->prn = Storage::get($prnFile);
        } else {
            $this->error = "El archivo $prnFile no existe.";
        }
    }

    /**
     * Reemplaza las variables [var0] [var1] etc, de una etiqueta por los valores en el array, segun su posicion.
     *
     * @param array $values
     */
    public function template($values=array()) {
        $this->prn = str_replace('[ENTER]','\0D',$this->prn);
        if(count($values)>0) {
            foreach($values as $index => $val) {
                $this->prn = str_replace('[var'.$index.']',$val,$this->prn);
            }
        }
    }

    /**
     * Crea una conexion socket para imprimir, guarda la respuesta en el parametro Zebra->error
     *
     * @return bool
     */
    public function imprimir(){
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            $this->error = "socket_create() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
            return false;
        }

        $result = socket_connect($socket, $this->host, $this->port);
        if ($result === false) {
            $this->error = "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
            return false;
        }

        socket_write($socket, $this->prn, strlen($this->prn));
        socket_close($socket);
        return true;
    }
}
