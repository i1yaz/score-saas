<?php

namespace App\Console;

use App\CronJobs\Landlord\CleanupCron;
use App\CronJobs\Landlord\Emails\EmailCron;
use Illuminate\Console\Scheduling\Schedule;
use App\CronJobs\Landlord\Gateways\RoutineTasks;
use App\CronJobs\EmailCron as RegularEmailCronJob;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\CronJobs\Landlord\Subscriptions\SubscriptionPaymentCron;
use App\CronJobs\Landlord\Subscriptions\SubscriptionActivatedCron;
use App\CronJobs\Landlord\Subscriptions\SubscriptionCancelledCron;
use App\CronJobs\Landlord\Subscriptions\SubscriptionPaymentFailedCron;

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
        $schedule->call(new EmailCron)->everyMinute();
        $schedule->call(new CleanupCron)->everyMinute();
        $schedule->call(new RoutineTasks)->everyMinute();
        $schedule->call(new SubscriptionActivatedCron)->everyMinute();
        $schedule->call(new SubscriptionCancelledCron)->everyMinute();
        $schedule->call(new SubscriptionPaymentCron)->everyMinute();
        $schedule->call(new SubscriptionPaymentFailedCron)->everyMinute();



        // -----------------------------------------------------------[TENANTS]-------------------------------------------------------------------

        //send [regular] queued emails
        $schedule->call(new RegularEmailCronJob)->everyMinute();
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
