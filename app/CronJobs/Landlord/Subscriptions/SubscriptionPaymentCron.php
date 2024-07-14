<?php

/** ---------------------------------------------------------------------------------------------------
 * [PROCESS SUBSCRIPTION PAYMENT - WEBHOOK]
 * This cronjob checks for webhooks marked as [subscription-payment]. Covering all payment gateways.
 * It will process the webhook as follows:
 *
 *       - add a new payment for the customer in the database\
 *       - set the subscription as active in both landlord & tenant databases
 *       - Send thank you for your payment email to client
 *       - Send new payment received email to admin
 *-----------------------------------------------------------------------------------------------------*/

namespace App\CronJobs\Landlord\Subscriptions;

use App\Models\Landlord\Payment;
use App\Models\Landlord\Setting;
use Exception;
use Illuminate\Support\Facades\Mail;
use Log;
use Spatie\Multitenancy\Models\Tenant;

class SubscriptionPaymentCron {

    public function __invoke(
    ) {

        //[MT] - landlord only
        if (\Spatie\Multitenancy\Models\Tenant::current()) {
            return;
        }

        //[MT] - run config settings for landlord
        runtimeLandlordCronConfig();

        /**
         *   - Find webhhoks waiting to be completed
         *   - mark the appropriate invoice as paid
         *   - ecords timeline event & notifications
         *   - Send thank you for your payment email to client
         *   - Send new payment received email to admin
         *   - Limit 20 emails at a time (for performance)
         */
        //Get the emails marked as [pdf] and [invoice] - limit 5
        $limit = 20;
        if ($webhooks = \App\Models\Landlord\Webhook::on('landlord')
            ->where('reference', 'subscription-payment')
            ->where('transaction_type', 'subscription')
            ->where('attempts', '<=', 3)
            ->where('status', 'new')->take($limit)->get()) {

            //Optimize:: mark all emails in the batch as processing - to avoid batch duplicates/collisions
            foreach ($webhooks as $webhook) {
                $webhook->update([
                    'status' => 'processing',
                ]);
            }

            //loop and process each webhook in the batch
            foreach ($webhooks as $webhook) {

                //check if there is a corresponding subscription for the payment session
                if (!$subscription = \App\Models\Landlord\Subscription::on('landlord')->Where('gateway_id', $webhook->gateway_reference)->first()) {

                    Log::info("webhook - [subscription-payment] - unable to find a corresnding subscription ($webhook->gateway_reference) for thie webhook [subscription-payment] - will now exit", ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $webhook]);

                    //we have reached max number of attempts
                    if ($webhook->attempts == 3) {
                        //log error
                        Log::error("no corresponding ($webhook->gateway_reference) was found", ['process' => '[landlord-cronjob][subscription-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        $webhook->update([
                            'status' => 'failed',
                            'comment' => "no corresponding subscription ($webhook->gateway_reference) was found",
                        ]);
                    } else {
                        Log::info("webhook - [subscription-payment] - the subscription ($webhook->gateway_reference) could not be found. it may still be in checkout (thank you page) process. will try again later", ['process' => '[landlord-cronjob][subscription-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        $webhook->update([
                            'attempts' => $webhook->attempts + 1,
                            'comment' => "the subscription could not be found. it may still be in checkout (thank you page) process. will try again later",
                        ]);
                    }

                    //skip to next webhook in the batch
                    continue;
                }

                Log::info("webhook - [subscription-payment] found a valid webhook to process - subscription id ($webhook->gateway_reference)", ['process' => '[landlord-cronjob][subscription-cancelled]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $webhook]);

                //check that his has not already been recorded
                if (Payment::on('landlord')->Where('transaction_id', $webhook->transaction_id)->exists()) {
                    Log::info("webhook - [subscription-payment] - a payment for this webhook already exists in the database. will now skip", ['process' => '[landlord-cronjob][subscription-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    $webhook->update([
                        'status' => 'completed',
                    ]);
                    continue;
                }

                /** -------------------------------------------------------------
                 * set the tenant's account as paid and send emails
                 * ------------------------------------------------------------*/
                if ($customer = Tenant::Where('id', $subscription->customerid)->first()) {

                    //create new payment
                    $payment = new Payment();
                    $payment->setConnection('landlord');
                    $payment->date = $webhook->payment_date;
                    $payment->tenant_id = $subscription->customer_id;
                    $payment->amount = $webhook->amount;
                    $payment->transaction_id = $webhook->transaction_id;
                    $payment->subscription_id = $subscription->id;
                    $payment->gateway = ucwords($webhook->source);
                    $payment->save();

                    //update subscription renewal dates
                    $subscription->date_renewed = $webhook->payment_date;
                    $subscription->date_next_renewal = $webhook->next_due_date;
                    $subscription->status = 'active';
                    $subscription->save();

                    //mark webhook cronjob as done
                    $webhook->update([
                        'status' => 'completed',
                    ]);

                    /** ----------------------------------------------------------------------
                     * update the tenants instance in their DB as [active]
                     * ---------------------------------------------------------------------*/
                    Tenant::forgetCurrent();
                    if ($tenant = Tenant::Where('id', $subscription->customer_id)->first()) {
                        try {
                            //swicth to this tenants DB
                            $tenant->makeCurrent();

                            //update customers account as cancelled
                            Setting::on('tenant')->where('id', 1)
                                ->update([
                                    'settings_saas_status' => 'active',
                                ]);

                            //mark customer as cancelled in landlord db
                            \App\Models\Landlord\Tenant::on('landlord')->where('id', $subscription->customer_id)
                                ->update([
                                    'status' => 'active',
                                ]);

                            //forget tenant
                            Tenant::forgetCurrent();

                        } catch (Exception $e) {
                            Log::error("webhook - [subscription-payment] - error trying to set the tenants database as [active] - error: " . $e->getMessage(), ['process' => '[landlord-cronjob][subscription-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'subscription_id' => $webhook->subscription_id, 'tenant_id' => $subscription->customer_id]);
                            $webhook->update([
                                'status' => 'failed',
                                'comment' => "unable to update the tenant database to status [active] - tenant id ($subscription->customer_id)",
                            ]);
                            continue;
                        }
                    }

                    /** ----------------------------------------------
                     * send thank you email to customer & admin
                     * ----------------------------------------------*/
                    $data = [
                        'customer_name' => $customer->name,
                        'customer_id' => $customer->id,
                        'payment_gateway' => 'Paypal',
                        'amount' => runtimeMoneyFormat($payment->amount),
                    ];

                    //email customer
                    $this->emailCustomer($data, $payment, $subscription);

                    //email admin
                    $this->emailAdmin($data, $payment, $subscription);

                }
            }
        }
        Log::info('SubscriptionPaymentCron: Ran successfully');
    }

    /**
     * Queue an email to the customer
     */
    public function emailCustomer($data, $payment, $subscription) {

        //email the customer
        if ($customer = \App\Models\Landlord\Tenant::on('landlord')->Where('id', $subscription->customer_id)->first()) {
            //queue email
            $mail = new \App\Mail\Landlord\Customer\PaymentConfirmation($customer, $data, $payment);
            $mail->build();
        }

    }

    /**
     * Queue an email to the admin
     */
    public function emailAdmin($data, $payment, $subscription) {

        //email admin users
        if ($admins = \App\Models\User::On('landlord')->Where('type', 'admin')->get()) {
            //queue email
            foreach ($admins as $user) {
                $mail = new \App\Mail\Landlord\Admin\NewPayment($user, $data, $payment);
                $mail->build();
            }
        }
    }

}
