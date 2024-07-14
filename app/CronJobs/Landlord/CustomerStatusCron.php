<?php

/** -------------------------------------------------------------------------------------------------
 * TEMPLATE
 * This cronjob is envoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *---------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs\Landlord;

use App\Models\Landlord\Tenant as LandlordTenant;
use DB;
use Exception;
use Log;
use Spatie\Multitenancy\Models\Tenant;

class CustomerStatusCron {

    public function __invoke() {
        if (Tenant::current()) {
            return;
        }
        //[MT] - run config settings for landlord
        runtimeLandlordCronConfig();

        //update statuses on the landlord DB
        $this->updateStatusesLandlordDB();

        //update statuses on the tenant DB
        $this->updateStatusesTenantDB();

        //forget
        \Spatie\Multitenancy\Models\Tenant::forgetCurrent();
    }

    /**
     * Update various customer subsccription statuses
     */
    public function updateStatusesLandlordDB() {

        $customers = \App\Models\Landlord\Tenant::on('landlord')
            ->select(['tenants.id as tenant_id','tenants.status as tenant_status','subscriptions.id as subscription_id','subscriptions.type as subscription_type',
            'subscriptions.status as subscription_status','subscriptions.trial_end','subscriptions.date_next_renewal'
            ])
            ->leftJoin('subscriptions', 'subscriptions.customer_id', '=', 'tenants.id')
            ->get();

        //get settings
        $settings = \App\Models\Landlord\Setting::on('landlord')->where('id', 'default')->first();

        foreach ($customers as $tenantCustomer) {
            $tenant = LandlordTenant::where('id', $tenantCustomer->tenant_id)->first();
            //[no subscription]
            if (!is_numeric($tenantCustomer->subscription_id) && $tenantCustomer->tenant_status != 'unsubscribed') {
                $tenant->status = 'unsubscribed';
                $tenant->save();
                continue;
            }

            //[free customer] - their status is not active
            if ($tenantCustomer->subscription_type == 'free' && $tenantCustomer->tenant_status != 'active') {
                $tenant->status = 'active';
                $tenant->save();
                continue;
            }

            //[wrong status]
            if ($tenantCustomer->tenant_status != 'unsubscribed' && ($tenantCustomer->tenant_status != $tenantCustomer->subscription_status)) {
                $tenant->status = $tenantCustomer->subscription_status;
                $tenant->save();
                continue;
            }

            //[free trial] check if free trial has an end date
            if ($tenantCustomer->subscription_type == 'paid' && $tenantCustomer->tenant_status == 'free-trial') {
                if ($tenantCustomer->trial_end == '' || $tenantCustomer->trial_end == '0000-00-00 00:00:00') {
                    $tenant->status = 'awaiting-payment';
                    $tenant->save();
                    continue;
                }
            }

            //[trial] check if free trial has not expired
            if ($tenantCustomer->subscription_type == 'paid' && $tenantCustomer->tenant_status == 'free-trial') {
                if (\Carbon\Carbon::parse($tenantCustomer->trial_end)->isPast()) {
                    $tenant->status = 'awaiting-payment';
                    $tenant->save();
                    \App\Models\Landlord\Subscription::on('landlord')->where('customer_id ', $tenantCustomer->tenant_id)->update(
                        [
                            'trial_end' => null,
                            'status' => 'awaiting-payment',
                        ]);
                    continue;
                }
            }

            //[expired] because no due date is set
            if ($tenantCustomer->subscription_type == 'paid' && $tenantCustomer->subscription_status == 'active') {
                if ($tenantCustomer->date_next_renewal == '' || $tenantCustomer->date_next_renewal == '0000-00-00 00:00:00') {
                    $tenant->status = 'awaiting-payment';
                    $tenant->save();
                    \App\Models\Landlord\Subscription::on('landlord')->where('customer_id ', $tenantCustomer->tenant_id)->update(
                        [
                            'trial_end' => null,
                            'status' => 'awaiting-payment',
                        ]);
                    continue;
                }
            }

            //[expired] because the next_renewal date (+ allowance) has passed
            if ($tenantCustomer->type == 'paid' && $tenantCustomer->status == 'active') {
                if (\Carbon\Carbon::parse($tenantCustomer->date_next_renewal)->addDays($settings->system_renewal_grace_period)->isPast()) {
                    $tenant->status = 'awaiting-payment';
                    $tenant->save();
                    continue;
                }
            }

        }

    }

    /**
     * Update the tenant datase with new subscription and account status
     *  @return array filename & filepath
     */
    public function updateStatusesTenantDB() {

        $customers = \App\Models\Landlord\Tenant::on('landlord')
            ->select(['tenants.id as tenant_id','tenants.status as tenant_status','subscriptions.id as subscription_id','subscriptions.type as subscription_type','subscriptions.package_id as subscription_package_id',])
            ->leftJoin('subscriptions', 'subscriptions.customer_id ', '=', 'tenants.id')
            ->get();

        foreach ($customers as $tenantCustomer) {

            \Spatie\Multitenancy\Models\Tenant::forgetCurrent();

            //get the customer from landlord db
            if ($tenant_customer = \Spatie\Multitenancy\Models\Tenant::Where('id', $tenantCustomer->tenant_id)->first()) {
                try {
                    //swicth to this tenants DB
                    $tenant_customer->makeCurrent();

                    //update the tenant record
                    DB::connection('tenant')
                        ->table('settings')
                        ->where('id', 1)
                        ->orWhere('id', 'default')
                        ->update([
                            'saas_status' => $tenantCustomer->tenant_status,
                            'saas_package_id' => $tenantCustomer->subscription_package_id,
                        ]);

                    //forget tenant
                    Tenant::forgetCurrent();

                } catch (Exception $e) {
                    Log::critical("error updating customers subscription status (" . $e->getMessage() . ")", ['process' => '[customer-status-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);
                }
            }

        }

    }
}
