<?php

namespace IAServer\Console\Commands;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Stat\StatExport;
use IAServer\Jobs\StartExportJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TestCommand:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing command.';

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
        $job = new StartExportJob(1,20,'2016-01-01','2016-01-05');

        dispatch($job);
        $this->info("TestCommand complete");
    }
}
