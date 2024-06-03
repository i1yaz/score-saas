<?php

namespace App\CronJobs\Landlord\Scheduled;

use App\Repositories\Landlord\DatabaseRepository;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;

class DeleteDatabasesCron {

    protected DatabaseRepository $databaseRepo;

    public function __invoke(DatabaseRepository $databaseRepo) {

        if (Tenant::current()) {
            return;
        }
        runtimeLandlordCronConfig();
        $this->databaseRepo = $databaseRepo;
        $this->deleteDatabases();
    }

    /**
     * Look for scheduled tasks to update [plan names] at the payment gateway
     * These are changes that were initiated when landlord updated some details about a package
     *  - Updates will be done at all payment gateways, one by one
     *
     * @notes
     * The schedule processing is set to 1 task per cycle. This is important when more payment gateways are enabled
     * in order to avoid server timeouts
     *
     * @return null
     */
    private function deleteDatabases() {

        //log that its run
        Log::info("Cronjob has started - (Delete Databases)", ['process' => '[landlord-cronjob][delete-databases-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //get some scheduled tasks
        $limit = 1;
        $count = 0;

        if ($scheduled = \App\Models\Landlord\Schedule::on('landlord')->Where('type', 'delete-database')
            ->Where('status', 'new')
            ->Where('attempts', '<=', 3)
            ->take($limit)->get()) {

            //loop through each one
            foreach ($scheduled as $schedule) {

                //database
                $database_name = $schedule->payload_1;

                //log
                Log::info("found a mysql database ($database_name) scheduled for deletion", ['process' => '[landlord-cronjob][delete-databases-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);

                //set to processing
                $schedule->status = 'processing';
                $schedule->save();

                //delete database
                if (!$this->databaseRepo->deleteDatabase($database_name)) {

                    //limit reached - mark for manual action
                    if ($schedule->attempts == 2) {
                        $schedule->manual_action_required = 'yes';
                        $schedule->comments = __('lang.database_needs_to_be_deleted_manually');
                    }

                    //save
                    $schedule->attempts = $schedule->attempts + 1;
                    $schedule->save();
                    Log::error("mysql database ($database_name) could not be deleted", ['process' => '[landlord-cronjob][delete-databases-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);
                    continue;
                }

                $count ++;

                $schedule->status = 'completed';
                $schedule->save();
                Log::info("mysql database ($database_name) deleted", ['process' => '[landlord-cronjob][delete-databases-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);
            }
        }

        Log::info("Cronjob has finished - Databases Deleted ($count)", ['process' => '[landlord-cronjob][delete-databases-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
    }

}
