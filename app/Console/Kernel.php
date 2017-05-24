<?php

namespace IAServer\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \IAServer\Console\Commands\AoicollectorStatExport::class,
        \IAServer\Console\Commands\ReworkStatExport::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
        /*$schedule->call(function () {
            Log::info("Tarea cada 5 minutos");
        })->everyFiveMinutes();

        $schedule->call(function () {
            Log::info("Tarea cada 10 minutos");
        })->everyTenMinutes();
*/
       /* $schedule->call(function () {
            $rv = new RedisView();
            $exec = $rv->index();
        })->everyMinute();*/
    }
}
