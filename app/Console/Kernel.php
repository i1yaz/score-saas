<?php

namespace App\Console;

use App\CronJobs\DirectEmailCron;
use App\CronJobs\Landlord\CleanupCron;
use App\CronJobs\Landlord\Emails\EmailCron;
use App\CronJobs\Landlord\SyncPackagesCron;
use Illuminate\Console\Scheduling\Schedule;
use App\CronJobs\Landlord\TenantsCronStatus;
use App\CronJobs\Landlord\CustomerStatusCron;
use App\CronJobs\Landlord\Gateways\RoutineTasks;
use App\CronJobs\EmailCron as RegularEmailCronJob;
use App\CronJobs\Landlord\Scheduled\UpdateEmailDomain;
use App\CronJobs\Landlord\Scheduled\DeleteDatabasesCron;
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
        define('BASE_DIR', realpath(__DIR__ . '/../../'));

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


        $schedule->call(new SyncPackagesCron)->everyFiveMinutes(); //[not less then 5 mins]
        $schedule->call(new DeleteDatabasesCron)->everyFiveMinutes(); //[not less then 5 mins]
        $schedule->call(new UpdateEmailDomain)->everyMinute();
        $schedule->call(new TenantsCronStatus)->everyMinute();
        $schedule->call(new CustomerStatusCron)->hourly();
        $schedule->call(new DeleteDatabasesCron)->everyMinute();



        // -----------------------------------------------------------[TENANTS]-------------------------------------------------------------------

        //send [regular] queued emails
        $schedule->call(new RegularEmailCronJob)->everyMinute();

         //send [direct] queued emails
         $schedule->call(new DirectEmailCron)->everyMinute();

//         //send pdf generating emails (invoice & estimate)
//         $schedule->call(new \App\Cronjobs\EmailBillsCron)->everyMinute();
//
//         //process webhooks for all onetime payments
//         $schedule->call(new \App\Cronjobs\Onetime\OnetimePayment)->everyMinute();

//         //process webhooks for stripe subscription payments
//         $schedule->call(new SubscriptionPayment)->everyMinute();
//
//         //process webhooks for stripe subscription renewal
//         $schedule->call(new SubscriptionRenewal)->everyMinute();
//
//         //process webhooks for stripe cancellation that was initiated in strip
//         $schedule->call(new SubscriptionCancelled)->everyMinute();
//
//         //process webhooks for stripe cancellation that was initiated in the dashboard
//         $schedule->call(new SubscriptionPushCancellation)->everyMinute();
//
//         //process webhooks for stripe subscription renewal
//         $schedule->call(new SubscriptionUpdateTransaction)->hourly();

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
