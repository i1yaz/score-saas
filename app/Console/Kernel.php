<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        if (\App::environment(['local'])) {
            $schedule->command('report:monthly-package-usage')
                ->everyFifteenMinutes()
                ->appendOutputTo(storage_path('logs/scheduler.log'));
        } else {
            $schedule->command('report:monthly-package-usage')
                ->monthlyOn(1, '0:00')
                ->everyFifteenMinutes()
                ->appendOutputTo(storage_path('logs/scheduler.log'));
        }

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
