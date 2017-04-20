<?php

namespace IAServer\Console\Commands;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Stat\StatExport;
use IAServer\Jobs\StartExportJob;
use Illuminate\Console\Command;

class AoicollectorStatExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AoicollectorStat:export {desde_maquina} {hasta_maquina} {fecha_desde?} {fecha_hasta?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta datos estadisticos de produccion de AOI.';

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

        $now = false;

        $fecha_desde = $this->argument('fecha_desde');
        $fecha_hasta  = $this->argument('fecha_hasta');
        $desde_maquina = $this->argument('desde_maquina');
        $hasta_maquina = $this->argument('hasta_maquina');

        if(empty($fecha_desde))
        {
            $fecha_desde = Carbon::now()->subDay();
        } else
        {
            if($fecha_desde == "now")
            {
                $now = true;
                $fecha_desde = Carbon::now();
            } else
            {
                $fecha_desde = Carbon::createFromFormat('Y-m-d',$fecha_desde);
            }
        }

        if(empty($fecha_hasta))
        {
            if($now)
            {
                $fecha_hasta = Carbon::now();
            } else
            {
                $fecha_hasta = Carbon::now()->subDay();
            }
        } else
        {
            $fecha_hasta = Carbon::createFromFormat('Y-m-d',$fecha_hasta);
        }

        //$fecha_desde = $fecha_desde->toDateString();
        //$fecha_hasta = $fecha_hasta->toDateString();

        while ($fecha_desde->lte($fecha_hasta)) {

            $fecha_current = $fecha_desde->copy()->toDateString();

            $this->info("Exportando: $fecha_current | Maquinas: ".$desde_maquina." a ".$hasta_maquina.", Espere...");

            // Agrego Job de exportacion, porque puede crashear al ejecutandolo directamente si la operacion demora mas de 300seg
            $job = new StartExportJob($desde_maquina,$hasta_maquina,$fecha_current);
            dispatch($job);

            //$stat->allMachinesToDb($desde_maquina,$hasta_maquina,$fecha_current);

            $fecha_desde->addDay();
        }

       // $this->info("Exportando: $fecha_desde hasta $fecha_hasta | Maquinas: ".$desde_maquina." a ".$hasta_maquina.", Espere...");
        //$stat->allMachinesToDb($desde_maquina,$hasta_maquina,$fecha_desde);
        $this->info("Operacion completa");
    }
}
