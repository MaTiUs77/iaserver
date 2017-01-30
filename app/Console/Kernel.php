<?php

namespace IAServer\Console;

use IAServer\Http\Controllers\Redis\RedisView;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \IAServer\Console\Commands\Inspire::class,
        \IAServer\Console\Commands\AoicollectorStatExport::class,
        \IAServer\Console\Commands\TestCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->dailyAt('04:00');

       /* $schedule->call(function () {
            Log::info("Schedule RUN");
        })->everyMinute();*/

       /* $schedule->call(function () {
            $rv = new RedisView();
            $exec = $rv->index();
        })->everyMinute();*/
    }
}
