<?php

/** -------------------------------------------------------------------------------------------------
 * This cronjob is envoked will look for any packages that have been updated by the landlord and
 * will sync the changes with every tenants database
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *---------------------------------------------------------------------------------------------------*/

namespace App\CronJobs\Landlord;

use DB;
use Exception;
use Log;
use Spatie\Multitenancy\Models\Tenant;

class SyncPackagesCron {

    public function __invoke() {
        if (Tenant::current()) {
            return;
        }
        //[MT] - run config settings for landlord
        runtimeLandlordCronConfig();

        //log that its run
        Log::info("Cronjob has started - (Syncronise Packages)", ['process' => '[landlord-cronjob][sync-packages-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //update the package features on the tenant database
        $this->SyncPackage();
        //forget
        Tenant::forgetCurrent();
    }

    /**
     * Sync any packages that have been updated with each tenants database
     *  @return array filename & filepath
     */
    public function SyncPackage() {

        //is there a package that needs to be synced (get just one at a time)
        if (!$package = \App\Models\Landlord\Package::On('landlord')
            ->Where('sync_status', 'awaiting-sync')
            ->first()) {
            Log::info("No packages were marked for update - finished", ['process' => '[landlord-cronjob][sync-packages-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return;
        }

        //mark processing
        $package->sync_status = 'syncing';
        $package->save();

        //are there any subscriptions using this package
        if (!$subscriptions = \App\Models\Landlord\Subscription::On('landlord')
            ->select('tenants.id as tenant_id')
            ->Where('package_id', $package->id)
            ->leftJoin('tenants', 'tenants.id', '=', 'subscriptions.customer_id')
            ->get()) {

            //mark packages as synced
            $package->sync_status = 'synced';
            $package->sync_date = now();
            $package->save();

            return;
            Log::info("No subscriptions were found that use the updated package - finished", ['process' => '[landlord-cronjob][sync-packages-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        }


        $count = 0;

        foreach ($subscriptions as $subscription) {

            Tenant::forgetCurrent();

            //get the customer from landlord db
            if ($customer = Tenant::Where('id', $subscription->tenant_id)->first()) {
                try {
                    //swicth to this tenants DB
                    $customer->makeCurrent();

                    //update the tenant record
                    DB::connection('tenant')
                        ->table('settings')
                        ->where('settings_id', 1)
                        ->update([
                            'saas_package_limits_student_packages' => $package->max_students,
                            'saas_package_limits_students' => $package->max_student_packages,
                            'saas_package_limits_monthly_packages' => $package->max_monthly_packages,
                            'saas_package_limits_tutors' => $package->max_tutors,
                        ]);

                    $count++;

                } catch (Exception $e) {
                    Log::error("error updating customers subscription status (" . $e->getMessage() . ")", ['process' => '[landlord-cronjob][sync-packages-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);
                }
            }

        }

        Log::info("Cronjob has finished - updated ($count) tenant databases with the new package settings", ['process' => '[landlord-cronjob][sync-packages-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //mark package as synced
        $package->sync_status = 'synced';
        $package->sync_date = now();
        $package->save();

    }
}
