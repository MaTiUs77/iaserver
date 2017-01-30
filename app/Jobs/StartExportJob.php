<?php

namespace IAServer\Jobs;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Stat\StatExport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class StartExportJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $desdeMaquina;
    protected $hastaMaquina;
    protected $fecha;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($desdeMaquina,$hastaMaquina, $fecha)
    {
        $this->desdeMaquina = $desdeMaquina;
        $this->hastaMaquina = $hastaMaquina;
        $this->fecha = $fecha;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $desdeMaquina = $this->desdeMaquina;
        $hastaMaquina = $this->hastaMaquina;
        $fecha = $this->fecha;

        $stat = new StatExport();

        Log::debug("StartExportJob: $fecha | Maquinas: ".$desdeMaquina." a ".$hastaMaquina);

        $stat->allMachinesToDb($desdeMaquina,$hastaMaquina,$fecha);

        Log::debug("StartExportJob $fecha complete");
    }
}
