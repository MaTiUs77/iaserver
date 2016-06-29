<?php

namespace IAServer\Console\Commands;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Stat\StatExport;
use Illuminate\Console\Command;

class AoicollectorStatExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AoicollectorStat:export {desde} {hasta} {fecha?}';

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
        $stat = new StatExport();

        $fecha = $this->argument('fecha');
        if(empty($fecha))
        {
            $fecha = Carbon::now()->subDay()->toDateString();
        } else
        {
            if($fecha=="now")
            {
                $fecha = Carbon::now()->toDateString();
            }
        }
        $this->info("Exportando: ".$fecha." | Maquinas: ".$this->argument('desde')." a ".$this->argument('hasta').", Espere...");
        $stat->allMachinesToDb($this->argument('desde'),$this->argument('hasta'),$fecha);
        $this->info("Operacion completa");
    }
}
