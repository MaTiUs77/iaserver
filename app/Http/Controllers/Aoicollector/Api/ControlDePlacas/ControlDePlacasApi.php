<?php
namespace IAServer\Http\Controllers\Aoicollector\Api\ControlDePlacas;

use IAServer\Http\Controllers\Aoicollector\Model\Stocker;
use IAServer\Http\Controllers\Aoicollector\Stocker\Trazabilidad\TrazaStocker;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Response;

/**
 * Webservice utilizado por el cliente Control de placas para verificar estado de placas
 *
 * @package IAServer\Http\Controllers\Aoicollector\Api\ControlDePlacas
 */
class ControlDePlacasApi extends TrazaStocker
{
    /**
     * Verifica si el stocker se encuentra declarado y listo para salir de la planta
     *
     * @param $stkbarcode
     */
    public function verifyStocker($stkbarcode) {
        $stkbarcode = strtoupper($stkbarcode);
        $output = $this->findStocker($stkbarcode,false);

        if (isset($output->stocker)) {
            $output->smt = SMTDatabase::findOp($output->stocker->op);
            $output->contenido = $this->stockerDeclaredDetail($output->stocker);
            $output->cuarentena = $this->stockerWithCarentena($output->contenido);
        } else {
            $output->error = 'El codigo de stocker no existe';
        }
        return Response::multiple($output);
    }

    /**
     * Informacion del stocker
     *
     * @param $stkbarcode
     * @return mixed
     */
    public function infoStocker($stkbarcode) {
        $stkbarcode = strtoupper($stkbarcode);
        $output = $this->findStocker($stkbarcode);

        if (isset($output->stocker)) {
            $output->smt = SMTDatabase::findOp($output->stocker->op);
        } else {
            $output->error = 'El codigo de stocker no existe';
        }

        return Response::multiple($output);
    }

    /**
     * Define la ruta del stocker en Control de Placas
     *
     * @param $stkbarcode
     * @return array|null|object
     */
    public function setRoute($stkbarcode)
    {
        $output = null;
        $stocker = $this->stockerInfoByBarcode($stkbarcode);
        if(isset($stocker->error)) {
            $output = $stocker;
        } else {
            if (isset($stocker->id)) {
                $stocker->sendToRouteId(2);
                $stocker = Stocker::findByIdStocker($stocker->id);
                $output = compact('stocker');
            }
        }

        return Response::multiple($output);
    }

    public function opinfo($op){
        $op = trim(strtoupper($op));
        $w = new Wip();
        $wip = $w->findOp($op,false);
        $smt = SMTDatabase::findOp($op);

        SMTDatabase::syncSmtWithWip($smt,$wip);

        $output = compact('op','wip','smt');
        return Response::multiple($output);
    }
}
