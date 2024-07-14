<?php

/** -------------------------------------------------------------------------------------------------
 * TenantsCronStatus
 * This cronjob is just used to record whether the tenant cronjobs have executed
 * This cron actually executes during the 'tenants' cron jobs run
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *---------------------------------------------------------------------------------------------------*/

namespace App\CronJobs\Landlord;


use App\Models\Setting;
use Spatie\Multitenancy\Models\Tenant;

class TenantsCronStatus {

    public function __invoke() {
        if (Tenant::current() == null) {
            return;
        }
        //boot system settings
        middlwareBootSystem();
        middlewareBootMail();

        //reset last cron run data (record in landlord db)
        Setting::On('landlord')
            ->where('id', 'default')
            ->orWhere('id', 1)
            ->update([
                'cronjob_has_run' => 'yes',
                'cronjob_last_run' => now(),
            ]);

    }

}
