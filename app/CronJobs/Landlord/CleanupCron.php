<?php

/** ---------------------------------------------------------------------------------------------------
 * Various cleanup and sanity task
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *-----------------------------------------------------------------------------------------------------*/

namespace App\CronJobs\Landlord;
use Illuminate\Support\Facades\Log;

class CleanupCron {

    public function __invoke() {
        runtimeLandlordCronConfig();
        $this->requeueStuckScheduledTasks();
    }

    private function requeueStuckScheduledTasks() {
        Log::info("Cleaup process - requeuing scheduled items that are stuck in 'processing' - started", ['process' => '[landlord-cronjob][cleanup-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        $x_hours_ago = \Carbon\Carbon::now()->subHours(1)->format('Y-m-d H:i:s');
        \App\Models\Landlord\Schedule::on('landlord')->Where('status', 'processing')
            ->Where('attempts', '<', 3)
            ->Where('updated_at', '<', $x_hours_ago)
            ->update(['status' => 'new']);
        Log::info("Cleaup process - requeuing scheduled items that are stuck in 'processing' - completed", ['process' => '[landlord-cronjob][cleanup-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return;
    }

}
