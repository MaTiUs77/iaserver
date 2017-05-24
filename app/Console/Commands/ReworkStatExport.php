<?php

namespace IAServer\Console\Commands;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Stat\StatExport;
use IAServer\Http\Controllers\Reparacion\Model\ReworkResume;
use IAServer\Http\Controllers\Reparacion\ReparacionController;
use IAServer\Jobs\StartExportJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class ReworkStatExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReworkExport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta datos de reparaciones ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$stat = new StatExport();
        $output = new ConsoleOutput();
        $ayer = Carbon::yesterday();
        $reparacionController = new ReparacionController();
        $sector = $reparacionController->getSectors();
        $output->writeln('Exportacion de datos de reparacion <fg=yellow>EN CURSO</> | '.Carbon::now()->toDateTimeString());
        $output->writeln('Exportando datos entre '.$ayer.' y '.$ayer.' [inclusive]');
        foreach($sector as $sec)
        {

            $reparaciones = $reparacionController->getReporte($sec->id,$ayer,$ayer);
            foreach($reparaciones as $reparacion)
            {
                $chkNotInDB = ReworkResume::where('codigo',$reparacion->codigo)
                                        ->where('fecha',$reparacion->fecha)
                                        ->where('hora',$reparacion->hora)
                                        ->get();
                if(count($chkNotInDB) == 0)
                {
                    $reworkResume = new ReworkResume();
                    $reworkResume->codigo = $reparacion->codigo;
                    $reworkResume->nombre = $reparacion->nombre;
                    $reworkResume->apellido = $reparacion->apellido;
                    $reworkResume->nombre_completo = $reparacion->nombre_completo;
                    $reworkResume->modelo = $reparacion->modelo;
                    $reworkResume->lote = $reparacion->lote;
                    $reworkResume->panel = $reparacion->panel;
                    $reworkResume->causa = $reparacion->causa;
                    $reworkResume->defecto = $reparacion->defecto;
                    $reworkResume->referencia = $reparacion->referencia;
                    $reworkResume->accion = $reparacion->accion;
                    $reworkResume->origen = $reparacion->origen;
                    $reworkResume->correctiva = $reparacion->correctiva;
                    $reworkResume->estado = $reparacion->estado;
                    $reworkResume->turno = $reparacion->turno;
                    $reworkResume->fecha = $reparacion->fecha;
                    $reworkResume->hora = $reparacion->hora;
                    $reworkResume->sector = $reparacion->sector;
                    $reworkResume->area = $reparacion->area;
                    $reworkResume->fotos = $reparacion->fotos;
                    $reworkResume->reparaciones = $reparacion->reparaciones;
                    $reworkResume->historico = $reparacion->historico;
                    $reworkResume->op = $reparacion->op;
                    $reworkResume->save();
                }
            }
            $output->writeln('Sector '.$sec->sector.' Done');
        }
        $output->writeln('Exportacion de datos de reparacion <fg=cyan>FINALIZADA</> | '.Carbon::now()->toDateTimeString());
    }
}
