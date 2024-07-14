<?php

namespace App\CronJobs\Landlord\Gateways;

use App\Models\Landlord\Package;
use App\Models\Landlord\Schedule;
use App\Repositories\Landlord\PaypalRepository;
use App\Repositories\Landlord\PaystackRepository;
use App\Repositories\Landlord\StripeRepository;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;

class RoutineTasks {

    protected $stripeRepo;
//    protected $paypalRepo;
//    protected $payStackRepo;

    public function __invoke(
        StripeRepository $stripeRepo,
//        PaypalRepository $paypalRepo,
//        PayStackRepository $payStackRepo

    ) {
        if (Tenant::current()) {
            return;
        }

        runtimeLandlordCronConfig();

        $this->stripeRepo = $stripeRepo;
//        $this->paypalRepo = $paypalRepo;
//        $this->payStackRepo = $payStackRepo;

        //update plan [name] at the payment gateways
        $this->updatePlanNames();

        //update plan [monthly price] at the payment gateways
        $this->updatePlanPrices('monthly');

        //update plan [yearly price] at the payment gateways
        $this->updatePlanPrices('yearly');

        //cancel any subscriptions at the payment gateways
        $this->cancelSubscriptions();

        //update the default product teh payment gateways like (Paypal)
        // $this->updateDefaultProduct();
        Log::info('RoutineTasks: Ran successfully');
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
    private function cancelSubscriptions() {
        $limit = 1;
        if ($scheduled = Schedule::on('landlord')->Where('type', 'cancel-subscription')
            ->Where('status', 'new')
            ->Where('attempts', '<=', 3)
            ->take($limit)->get()) {
            foreach ($scheduled as $schedule) {
                $errors = false;
                $payment_gateway = $schedule->gateway;
                Log::info("found a scheduled task to cancel a subscription at payment gateway ($payment_gateway)", ['process' => '[payment-gateways-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);
                $schedule->status = 'processing';
                $schedule->save();

                //[stripe]
                if ($payment_gateway == 'stripe') {
                    if ($this->stripeRepo->cancelSubscription([
                        'stripe_secret_key' => config('system.stripe_secret_key'),
                        'subscription_stripe_id' => $schedule->payload_1,
                    ])) {
                        $schedule->status = 'completed';
                        $schedule->save();
                        continue;
                    } else {
                        $errors = true;
                    }
                }

//                //[paypal]
//                if ($payment_gateway == 'paypal') {
//                    if ($this->paypalRepo->cancelSubscription($schedule->scheduled_payload_1)) {
//                        $schedule->scheduled_status = 'completed';
//                        $schedule->save();
//                        continue;
//                    } else {
//                        $errors = true;
//                    }
//                }
//
//                //[paystack]
//                if ($payment_gateway == 'paystack') {
//                    if ($this->paystackrepo->cancelSubscription([
//                        'subscription_id' => $schedule->scheduled_payload_1,
//                        'paystack_token' => $schedule->scheduled_payload_5,
//                        'settings_paystack_secret_key' => config('system.settings_paystack_secret_key'),
//                    ])) {
//                        $schedule->scheduled_status = 'completed';
//                        $schedule->save();
//                        continue;
//                    } else {
//                        $errors = true;
//                    }
//                }

                //error were reported
                if ($errors) {
                    //try again later
                    $schedule->attempts = $schedule->attempts + 1;
                    $schedule->save();
                    //maximum tries reached
                    if ($schedule->attempts == 3) {
                        $schedule->status = 'failed';
                        $schedule->comments = '[cancel subscription] could not be updated at one or more payment gateways. See error logs for details. Ref: ' . config('app.debug_ref');
                        $schedule->save();
                        Log::error("scheduled task to update [cancel subscription] at payment gateway ($payment_gateway) failed - max tries reached - Ref: " . config('app.debug_ref'), ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);
                    }
                }
            }
        }
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
    private function updatePlanNames() {

        $limit = 1;
        if ($scheduled = Schedule::on('landlord')->Where('type', 'update-plan-name')
            ->Where('status', 'new')
            ->Where('attempts', '<=', 3)
            ->take($limit)->get()) {

            foreach ($scheduled as $schedule) {

                //reset
                $errors = false;

                //log
                Log::info("found a scheduled task to update a plan's name at the payment gateways", ['process' => '[payment-gateways-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);

                //set to processing
                $schedule->status = 'processing';
                $schedule->save();

                //get the package
                if (!$package = Package::on('landlord')->Where('id', $schedule->payload_1)->first()) {
                    Log::info("the package linked to this schedule nolonger exixts. will now skip.", ['process' => '[payment-gateways-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id, 'package_id' => $schedule->payload_1]);
                    $schedule->status = 'completed';
                    $schedule->comments = 'The package linked to this schedule nolonger exixts - task has been skipped';
                    $schedule->save();
                    return;
                }

                //[stripe]
                if (!$this->updatePlanNameStripe($package)) {
                    $errors = true;
                }
                if ($errors) {
                    $schedule->attempts = $schedule->attempts + 1;
                    $schedule->save();
                    if ($schedule->attempts == 3) {
                        $payment_gateway = $payment_gateway??'Stripe';
                        $schedule->status = 'failed';
                        $schedule->comments = '[plan name] could not be updated at one or more payment gateways. See error logs for details. Ref: ' . config('app.debug_ref');
                        $schedule->save();
                        Log::error("scheduled task to update [plan name] at payment gateway ($payment_gateway) failed - max tries reached - Ref: " . config('app.debug_ref'), ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);
                    }
                } else {
                    $schedule->status = 'completed';
                    $schedule->save();
                }
            }
        }
    }

    /**
     * Update plan pricing at the payment gwateway, to match the price changes done to the package by the admin.
     *  - Updates will be done at all payment gateways that are enabled. The reason being that when a gateway is disabled, all plan_id's are also delete.
     *    this means new plans will be created anyway,for that gateway, when a user next checks outs.
     *  - Most payment gateways have immutable plan prices. This means we should just archive all old plans and create new ones
     *
     * @notes
     * The schedule processing is set to 1 task per cycle. This is important when more payment gateways are enabled
     * in order to avoid server timeouts
     *
     * @param  int  $cycle (monthly|yearly)
     * @return bool
     */
    private function updatePlanPrices($cycle = '') {

        $scheduled_type = ($cycle == 'monthly') ? 'update-plan-monthly-price' : 'update-plan-yearly-price';

        //get some scheduled tasks
        $limit = 1;
        if ($scheduled = Schedule::on('landlord')->Where('type', $scheduled_type)
            ->Where('status', 'new')
            ->Where('attempts', '<=', 3)
            ->take($limit)->get()) {
            foreach ($scheduled as $schedule) {
                $errors = false;
                Log::info("found a scheduled task to update a plan's price at the payment gateways", ['process' => '[payment-gateways-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->id]);
                $schedule->status = 'processing';
                $schedule->save();

                //get the package
                if (!$package = Package::on('landlord')->Where('package_id', $schedule->payload_1)->first()) {
                    $schedule->status = 'failed';
                    $schedule->comments = 'Updating plan price at payment gateways failed - The package no longer exists in the crm. Ref: ' . config('app.debug_ref');
                    $schedule->save();
                    return false;
                }

                //[STRIPE] (monthly or yearly)
                if (config('system.stripe_status') == 'enabled') {
                    if (!$this->stripeRepo->updatePlanPrice($package, [
                        'stripe_secret_key' => config('system.stripe_secret_key'),
                        'product_id' => ($cycle == 'monthly') ? $package->gateway_stripe_product_monthly : $package->gateway_stripe_product_yearly,
                        'price_id' => ($cycle == 'monthly') ? $package->gateway_stripe_price_monthly : $package->gateway_stripe_price_yearly,
                        'price_amount' => ($cycle == 'monthly') ? $package->amount_monthly * 100 : $package->amount_yearly * 100,
                        'price_cycle' => ($cycle == 'monthly') ? 'month' : 'year',
                        'price_currency' => strtoupper(config('system.system_currency_code')),
                    ])) {
                        $errors = true;
                    }
                }

                //[PAYPAL] (monthly or yearly)
//                if (config('system.settings_paypal_status') == 'enabled') {
//                    if (!$this->paypalrepo->updatePlanPrice($package, [
//                        'product_id' => config('system.settings_paypal_subscription_product_id'),
//                        'plan_name' => $package->package_name,
//                        'plan_id' => ($cycle == 'monthly') ? $package->package_gateway_paypal_plan_monthly : $package->package_gateway_paypal_plan_yearly,
//                        'plan_cycle' => ($cycle == 'monthly') ? 'MONTH' : 'YEAR',
//                        'plan_amount' => ($cycle == 'monthly') ? $package->package_amount_monthly : $package->package_amount_yearly,
//                        'plan_currency' => strtoupper(config('system.settings_system_currency_code')),
//                        'plan_description' => __('lang.crm_subscription'),
//                    ])) {
//                        $errors = true;
//                    }
//                }

                //[PAYSTACK] (monthly or yearly)
//                if (config('system.settings_paystack_status') == 'enabled') {
//                    if (!$this->paystackrepo->updatePlanPrice($package, [
//                        'paystack_secret_key' => config('system.settings_paystack_secret_key'),
//                        'plan_name' => $package->package_name,
//                        'plan_id' => ($cycle == 'monthly') ? $package->package_gateway_paystack_plan_monthly : $package->package_gateway_paystack_plan_yearly,
//                        'plan_amount' => ($cycle == 'monthly') ? $package->package_amount_monthly * 100 : $package->package_amount_yearly * 100,
//                        'plan_cycle' => ($cycle == 'monthly') ? 'monthly' : 'annually',
//                        'plan_currency' => strtoupper(config('system.settings_system_currency_code')),
//                    ])) {
//                        $errors = true;
//                    }
//                }
                $payment_gateway = $payment_gateway??'Stripe';
                //error were reported
                if ($errors) {
                    //try again later
                    $schedule->attempts = $schedule->attempts + 1;
                    $schedule->save();
                    //maximum tries reached
                    if ($schedule->attempts == 3) {
                        $schedule->status = 'failed';
                        $schedule->comments = '[plan price] could not be updated at one or more payment gateways. See error logs for details. Ref: ' . config('app.debug_ref');
                        $schedule->save();
                        Log::error("scheduled task to update [plan price] at payment gateway ($payment_gateway) failed - max tries reached - Ref: " . config('app.debug_ref'), ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->scheduled_id]);
                    }
                } else {
                    $schedule->scheduled_status = 'completed';
                    $schedule->save();
                }
            }
        }
    }

    /**
     * Update the [name] of the subscription plan at the payment gateway so that it willmatch the name used in the package
     *
     * @notes
     *   - Stripe uses the name 'Products' which are the same as 'Plans' for us
     *   - Error logging is mostly done in the reporsitories (e.g. striperepository class)
     *
     * @param  obj  $package landlord subscription packages/plans
     * @return bool
     */
    public function updatePlanNameStripe($package) {

        //check if stripe is enabled
        if (config('system.stripe_status') != 'enabled') {
            return true;
        }
        $success = false;
        //update monthly plan [name]
        if ($package->gateway_stripe_product_monthly != '') {
            if ($this->stripeRepo->updatePlanName([
                'stripe_secret_key' => config('system.stripe_secret_key'),
                'plan_id' => $package->gateway_stripe_product_monthly,
                'plan_name' => $package->name,
            ])) {
                $success = true;
            }
        }

        //update yearly plan [name]
        if ($package->package_gateway_stripe_product_yearly != '') {
            if ($this->stripeRepo->updatePlanName([
                'stripe_secret_key' => config('system.stripe_secret_key'),
                'plan_id' => $package->package_gateway_stripe_product_yearly,
                'plan_name' => $package->package_name,
            ])) {
                $success = true;
            }
        }

        //result
        if ($success) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Payment gateays like Paypal are using a single "Default Product"
     * If this products name and description have been changed via the landlord dashboard, do the following:
     *
     *  [notes][13-nov-2022]
     *    - [paypal] so far,this is not required as the name not show up at checkout, so no real need to keep changing it
     *
     * @return null
     */
//    public function updateDefaultProduct() {
//
//        if ($schedule = Schedule::on('landlord')->Where('type', 'update-default-product-name')
//            ->Where('status', 'new')
//            ->Where('attempts', '<=', 3)->first()) {
//            $errors = false;
//
//            //log
//            Log::info("found a scheduled task to update the [default product] at applicable payment gateways", ['process' => '[payment-gateways-cron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->scheduled_id]);
//
//            //error were reported
//            if ($errors) {
//                //try again later
//                $schedule->scheduled_attempts = $schedule->scheduled_attempts + 1;
//                $schedule->save();
//                //maximum tries reached
//                if ($schedule->scheduled_attempts == 3) {
//                    $schedule->scheduled_status = 'failed';
//                    $schedule->scheduled_comments = '[default plan name] could not be updated at one or more payment gateways. See error logs for details. Ref: ' . config('app.debug_ref');
//                    $schedule->save();
//                    Log::error("scheduled task to update [default plan name] at payment gateway ($payment_gateway) failed - max tries reached - Ref: " . config('app.debug_ref'), ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'schedule_id' => $schedule->scheduled_id]);
//                }
//            } else {
//                $schedule->scheduled_status = 'completed';
//                $schedule->save();
//            }
//
//        }
//
//    }

}
