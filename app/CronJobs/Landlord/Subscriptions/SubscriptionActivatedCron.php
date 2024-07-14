<?php

/** ---------------------------------------------------------------------------------------------------
 * [PROCESS SUBSCRIPTION ACTIVATED - WEBHOOK]
 * This cronjob checks for webhooks marked as [subscription-activated]. Covering all payment gateways.
 * It will process the webhook as follows:
 *
 *       - marks the subscription as active in both landlod and tenant databases
 *       - updates the subscriptions next renewal date, in the landlord database
 *
 *-----------------------------------------------------------------------------------------------------*/

namespace App\CronJobs\Landlord\Subscriptions;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;

class SubscriptionActivatedCron {

    public function __invoke(
        UserRepository $userrepo
    ) {

        //[MT] - landlord only
        if (\Spatie\Multitenancy\Models\Tenant::current()) {
            return;
        }

        //[MT] - run config settings for landlord
        runtimeLandlordCronConfig();

        //forget tenant
        Tenant::forgetCurrent();

        /**
         *   - Find web hooks waiting to be processes
         *   - Find the subscription and cancel it in the crm db
         *   - set the tenants account as [cancelled]
         */
        $limit = 5;
        if ($webhooks = \App\Models\Landlord\Webhook::on('landlord')
            ->where('reference', 'subscription-activated')
            ->where('transaction_type', 'subscription')
            ->where('status', 'new')->take($limit)->get()) {

            // Optimize:: mark all emails in the batch as processing - to avoid batch duplicates/collisions
            foreach ($webhooks as $webhook) {
                $webhook->update([
                    'webhooks_status' => 'processing',
                ]);
            }

            //loop and process each webhook in the batch
            foreach ($webhooks as $webhook) {

                //get the subscription from db
                if ($subscription = \App\Models\Landlord\Subscription::on('landlord')->Where('gateway_id', $webhook->gateway_reference)->first()) {

                    Log::info("webhook - [Subscription-activated] - found a valid webhook to process - subscription id ($webhook->gateway_reference) - will now pocess", ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $webhook]);

                    //update subscription as [active] in the landlord db
                    $subscription->status = 'active';
                    $subscription->gateway_name = strtolower($webhook->source);
                    $subscription->date_next_renewal = $webhook->next_due_date;
                    $subscription->save();

                    /** ----------------------------------------------------------------------
                     * update the tenants instance in their DB as [active]
                     * ---------------------------------------------------------------------*/
                    Tenant::forgetCurrent();
                    if ($customer = Tenant::Where('id', $subscription->customer_id)->first()) {
                        try {
                            //swicth to this tenants DB
                            $customer->makeCurrent();

                            //update customers account as cancelled
                            \App\Models\Landlord\Setting::on('tenant')->where('id', 1)
                                ->update([
                                    'saas_status' => 'active',
                                ]);

                            //mark customer as cancelled in landlord db
                            \App\Models\Landlord\Tenant::on('landlord')->where('id', $subscription->customer_id)
                                ->update([
                                    'status' => 'active',
                                ]);
                            Tenant::forgetCurrent();

                        } catch (Exception $e) {
                            Log::error("webhook - [Subscription-activated] - error trying to set the tenants database as [active] - error: " . $e->getMessage(), ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'subscription_id' => $webhook->subscription_id, 'tenant_id' => $subscription->customer_id]);
                            $webhook->update([
                                'status' => 'failed',
                                'comment' => "unable to update the tenant database to status [active] - tenant id ($subscription->customer_id)",
                            ]);
                            continue;
                        }
                    }
                } else {
                    Log::info("webhook - [Subscription-activated] - unable to find a corresnding subscription ($webhook->gateway_reference) in the landlord database] - will now exit", ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $webhook]);
                }

                //mark webhook cronjob as done
                $webhook->update([
                    'status' => 'completed',
                ]);

            }

        }
        Log::info('SubscriptionActivatedCron: Ran successfully');
    }
}
