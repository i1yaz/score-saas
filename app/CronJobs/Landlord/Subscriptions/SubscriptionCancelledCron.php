<?php

/** ---------------------------------------------------------------------------------------------------
 * [PROCESS SUBSCRIPTION CANCELLED - WEBHOOK]
 * This cronjob checks for webhooks marked as [subscription-cancelled]. Covering all payment gateways.
 * It will process the webhook as follows:
 *
 *       - cancell the subscription in the landlord database
 *       - mark the tenants database record as cancelled (settings)
 *       - Sends notification email to the customer
 *       - Sends notification email to the admin
 *-----------------------------------------------------------------------------------------------------*/

namespace App\CronJobs\Landlord\Subscriptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;

class SubscriptionCancelledCron {

    public function __invoke() {

        //[MT] - landlord only
        if (Tenant::current()) {
            return;
        }

        //[MT] - run config settings for landlord
        runtimeLandlordCronConfig();
        Tenant::forgetCurrent();

        /**
         *   - Find web hooks waiting to be processes
         *   - Find the subscription and cancel it in the crm db
         *   - set the tenants account as [cancelled]
         */
        $limit = 5;
        if ($webhooks = \App\Models\Landlord\Webhook::on('landlord')
            ->where('reference', 'subscription-cancelled')
            ->where('transaction_type', 'subscription')
            ->where('status', 'new')->take($limit)->get()) {

            //mark all emails in the batch as processing - to avoid batch duplicates/collisions
            foreach ($webhooks as $webhook) {
                $webhook->update([
                    'status' => 'processing',
                ]);
            }

            //loop and process each webhook in the batch
            foreach ($webhooks as $webhook) {

                //get the subscription from db
                if ($subscription = \App\Models\Landlord\Subscription::on('landlord')->Where('gateway_id', $webhook->gateway_reference)->first()) {

                    Log::info("webhook - [Subscription-cancelled] - found a valid webhook to process - subscription id ($webhook->gateway_reference)", ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $webhook]);

                    /** ----------------------------------------------------------------------
                     * update the tenants instance in their DB as [cancelled]
                     * ---------------------------------------------------------------------*/
                    Tenant::forgetCurrent();
                    if ($customer = Tenant::Where('id', $subscription->customer_id)->first()) {
                        try {
                            //swicth to this tenants DB
                            $customer->makeCurrent();

                            //update customers account as cancelled
                            \App\Models\Landlord\Setting::on('tenant')->where('id', 1)
                                ->update([
                                    'saas_status' => 'unsubscribed',
                                    'saas_package_id' => null,
                                    'saas_package_limits_clients' => 0,
                                    'saas_package_limits_team' => 0,
                                    'saas_package_limits_projects' => 0,
                                ]);

                            //mark customer as cancelled in landlord db
                            \App\Models\Landlord\Tenant::on('landlord')->where('id', $subscription->customer_id)
                                ->update([
                                    'status' => 'unsubscribed',
                                ]);

                            //forget tenant
                            Tenant::forgetCurrent();

                            /*--------------------------------------------------------
                             * send out emails
                             * ------------------------------------------------------*/
                            $data = [
                                'subscription_id' => $subscription->id,
                                'subscription_amount' => runtimeMoneyFormat($subscription->amount) . 'SubscriptionCancelledCron.php/' . runtimeLang($subscription->gateway_billing_cycle),
                            ];

                            //get the plan
                            if ($package = \App\Models\Landlord\Package::on('landlord')->Where('id', $subscription->package_id)->first()) {
                                $data['plan_name'] = $package->package_name;
                            }

                            if ($customer = \App\Models\Landlord\Tenant::on('landlord')->Where('id', $subscription->customer_id)->first()) {
                                $data['customer_name'] = $customer->tenant_name;
                                $data['customer_url'] = 'https://' . $customer->domain;
                            }

                            //email customer
                            $this->emailCustomer($data, $subscription);

                            //email admin
                            $this->emailAdmin($data, $subscription);

                        } catch (Exception $e) {
                            Log::error("webhook - [Subscription-cancelled] - error trying to update the tenants database as [cancelled] - error: " . $e->getMessage(), ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'subscription_id' => $webhook->subscription_id, 'tenant_id' => $subscription->subscription_customerid]);
                            $webhook->update([
                                'status' => 'failed',
                                'comment' => "unable to update the tenant database to status [cancelled] - tenant id ($subscription->customerid)",
                            ]);
                            continue;
                        }
                    }

                    //update subscription record
                    $subscription->archived = 'yes';
                    $subscription->status = 'cancelled';
                    $subscription->save();
                } else {
                    Log::info("webhook - [Subscription-cancelled] - unable to find a corresnding subscription ($webhook->gateway_reference) in the landlord database - will now exit", ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $webhook]);
                }

                //mark webhook cronjob as done
                $webhook->update([
                    'status' => 'completed',
                ]);

            }

        }
        Log::info('SubscriptionCancelledCron: Ran successfully');
    }

    /**
     * Queue an email to the customer
     *
     * @param  array  $data
     * @param  obj  $subscription
     * @return null
     */
    public function emailCustomer($data, $subscription) {

        //email the customer
        if ($customer = \App\Models\Landlord\Tenant::on('landlord')->Where('id', $subscription->customer_id)->first()) {
            //queue email
            $mail = new \App\Mail\Landlord\Customer\SubscriptionCancelled($customer, $data, $subscription);
            $mail->build();
        }

    }

    /**
     * Queue an email to the admin
     *
     * @param  array  $data
     * @param  obj  $subscription
     * @return null
     */
    public function emailAdmin($data, $subscription) {

        //email admin users
        if ($admins = \App\Models\User::On('landlord')->Where('type', 'admin')->get()) {
            //queue email
            foreach ($admins as $user) {
                $mail = new \App\Mail\Landlord\Admin\SubscriptionCancelled($user, $data, $subscription);
                $mail->build();
            }
        }
    }
}
