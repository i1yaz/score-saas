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
        $schedule->call(new \App\Cronjobs\Landlord\Emails\EmailCron)->everyMinute();
        $schedule->call(new \App\Cronjobs\Landlord\CleanupCron)->everyMinute();
        $schedule->call(new \App\Cronjobs\Landlord\Gateways\RoutineTasks)->everyMinute();
        $schedule->call(new \App\Cronjobs\Landlord\Subscriptions\SubscriptionActivatedCron)->everyMinute();
        $schedule->call(new \App\Cronjobs\Landlord\Subscriptions\SubscriptionCancelledCron)->everyMinute();
        $schedule->call(new \App\Cronjobs\Landlord\Subscriptions\SubscriptionPaymentCron)->everyMinute();
        $schedule->call(new \App\Cronjobs\Landlord\Subscriptions\SubscriptionPaymentFailedCron)->everyMinute();



        // -----------------------------------------------------------[TENANTS]-------------------------------------------------------------------

        //send [regular] queued emails
        $schedule->call(new \App\Cronjobs\EmailCron)->everyMinute();
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
