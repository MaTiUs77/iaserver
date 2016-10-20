<?php
namespace IAServer\Http\Controllers\Aoicollector\Api;

use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class Api extends Controller
{
    public function verifyPlaca($barcode,$stage)
    {
        $output = new \stdClass();

        if(ctype_alnum($stage)){

            $findPanel = new FindInspection();
            $findPanel->withSmt = true;
            $findPanel->onlyLast = true;

            $panel = $findPanel->barcode($barcode);

            if(isset($panel->last))
            {
                $data = $panel->last;
                $panel = $data->panel;

                $output->barcode = $barcode;
                $output->op = $panel->inspected_op;
                $output->smt = $data->smt;

                // Verifico si el panel es secundario
                if($panel->isSecundario())
                {
                    $verify = new VerificarDeclaracion();
                    $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);
                    $output->declaracion = $interfazWip->declaracion;
                } else
                {
                    $verify = new VerificarDeclaracion();
                    $interfazWip = $verify->panelEnTransaccionesWipOrCheckInterfazWip($panel);
                    $output->declaracion = $interfazWip->declaracion;
                }
                // Esconder algunos datos
                unset($output->declaracion->parcial);
                unset($output->declaracion->declarado_total);
                unset($output->declaracion->parcial_total);
                unset($output->declaracion->pendiente_total);
                unset($output->declaracion->error_total);
            } else
            {
                $output = $panel;
            }
        }
        else{
            $output->error = 'El nombre de referencia no es alphanumerico';
        }
        return $output;
    }

    public function fullInfo($barcode)
    {
        $output = new \stdClass();

        $findPanel = new FindInspection();
        $findPanel->withSmt = true;
        $findPanel->withHistory = false;

        $panel = $findPanel->barcode($barcode);

        if(isset($panel->last))
        {
            $data = $panel->last;
            $panel = $data->panel;

            $output->barcode = $barcode;
            $output->op = $panel->inspected_op;
            $output->smt = $data->smt;

            // Verifico si el panel es secundario
            if($panel->isSecundario())
            {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelSecundarioEnInterfazWip($panel);
                $output->declaracion = $interfazWip->declaracion;
            } else
            {
                $verify = new VerificarDeclaracion();
                $interfazWip = $verify->panelEnTransaccionesWipOrCheckInterfazWip($panel);
                $output->declaracion = $interfazWip->declaracion;
            }
            // Esconder algunos datos
            unset($output->declaracion->parcial);
            unset($output->declaracion->declarado_total);
            unset($output->declaracion->parcial_total);
            unset($output->declaracion->pendiente_total);
            unset($output->declaracion->error_total);
        } else
        {
            $output = $panel;
        }

        return $output;
    }
}
