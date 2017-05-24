<?php

namespace IAServer\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use IAServer\Http\Controllers\Aoicollector\Stat\StatExport;
use IAServer\Http\Controllers\Reparacion\Model\ReworkResume;
use IAServer\Http\Controllers\Reparacion\ReparacionController;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class ExportReworksJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

//    protected $desdeMaquina;
//    protected $hastaMaquina;
//    protected $fecha;
    /**
     * Create a new job instance.
     *
     * @return void
     */
//    public function __construct($desdeMaquina,$hastaMaquina, $fecha)
//    {
//        $this->desdeMaquina = $desdeMaquina;
//        $this->hastaMaquina = $hastaMaquina;
//        $this->fecha = $fecha;
//    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


    }
}
