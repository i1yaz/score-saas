<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\Package;
use App\Models\Landlord\Payment;
use App\Models\Landlord\Schedule;
use App\Models\Landlord\Subscription;
use App\Models\Landlord\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionsRepository
{

    //repos
    protected Subscription $subscription;
    protected StripeRepository $striperepo;
    private CheckoutRepository $checkoutRepo;

    /**
     * Inject dependecies
     */
    public function __construct(
        Subscription $subscription,
        StripeRepository $stripeRepo,
        CheckoutRepository $checkoutRepo) {

        $this->subscription = $subscription;
        $this->striperepo = $stripeRepo;
        $this->checkoutRepo = $checkoutRepo;

    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object subscriptions collection
     */
    public function search($id = '',$columns = ['*']) {
        $subscriptions = $this->subscription->newQuery();
        $subscriptions->select($columns);
        $subscriptions->leftJoin('users', 'users.id', '=', 'subscriptions.added_by');
        $subscriptions->leftJoin('tenants', 'tenants.id', '=', 'subscriptions.customer_id');

//        //filters: id
//        if (request()->filled('filter_subscription_id')) {
//            $subscriptions->where('subscription_id', request('filter_subscription_id'));
//        }
//        if (is_numeric($id)) {
//            $subscriptions->where('subscription_id', $id);
//        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $subscriptions->where(function ($query) {
                $query->orWhere('gateway_id', '=', request('search_query'));
                $query->orWhere('gateway_name', '=', request('search_query'));
                $query->orWhere('date_renewed', '=', request('search_query'));
                $query->orWhere('date_started', '=', request('search_query'));
                $query->orWhere('final_amount', '=', request('search_query'));
            });
        }

        //sorting Note: need to configure
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            if (Schema::hasColumn('subscriptions', request('orderby'))) {
                $subscriptions->orderBy(request('orderby'), request('sortorder'));
            }
        } else {
            $subscriptions->orderBy('subscriptions.id', 'desc');
        }

        // Get the results and return them.
        return $subscriptions->paginate(config('system.system_pagination_limits'));
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create() {

        //save new user
        $subscription = new $this->subscriptions;

        //data
        $subscription->subscription_categoryid = request('subscription_categoryid');
        $subscription->subscription_creatorid = auth()->id();

        //save and return id
        if ($subscription->save()) {
            return $subscription->subscription_id;
        } else {
            Log::error("unable to create record - database error", ['process' => '[ItemRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * change a customers subscription as follows
     *   (1) delete existing subscription
     *   (2) create a new subscription
     *   (3) update the tenant database with the new subscription status
     *
     * @param array $data data payload
     * @return mixed int|bool
     */
    public function changeCustomersPlan($data) {

        //changing customers subscription
        Log::info("changing customers subscription plan - started", ['process' => '[change-customers-subscription-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);

        //get current subscription from database
        if (!$customer = Tenant::On('landlord')->Where('id', $data['customer_id'])->first()) {
            Log::error("customer could not be found in the tenant database", ['process' => '[change-customers-subscription-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
            return false;
        }

        //get the package
        if (!$package = Package::On('landlord')->Where('id', $data['package_id'])->first()) {
            Log::error("the package could not be found", ['process' => '[change-customers-subscription-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
            return false;
        }

        //remove existing subscriptions (set it to
        if ($subscriptions = Subscription::On('landlord')->Where('customer_id', $data['customer_id'])->get()) {
            foreach ($subscriptions as $subscription) {

                //queue for cancelling at the payment gateway (will be done via cronjob)
                if ($subscription->type == 'paid' && $subscription->payment_method == 'automatic') {
                    if ($subscription->status == 'active' || $subscription->status == 'failed') {
                        $schedule = new Schedule();
                        $schedule->setConnection('landlord');
                        $schedule->gateway = $subscription->gateway_name;
                        $schedule->type = 'cancel-subscription';
                        $schedule->payload_1 = $subscription->gateway_id;
                        $schedule->payload_2 = $subscription->checkout_reference_2;
                        $schedule->payload_3 = $subscription->checkout_reference_3;
                        $schedule->payload_4 = $subscription->checkout_reference_4;
                        $schedule->payload_5 = $subscription->checkout_reference_5;
                        $schedule->save();
                    }
                }
                $archive = true;

                //if it was a free plan - delete it
                if ($subscription->type == 'free') {
                    $subscription->delete();
                    $archive = false;
                }

                //if it had no previous payments - delete it
                if (Payment::On('landlord')->Where('subscription_id', $subscription->id)->doesntExist()) {
                    $subscription->delete();
                    $archive = false;
                }

                //archive subscription record - this subscription was once active and paid - let us keep it in our database
                if ($archive) {
                    $subscription->archived = 'yes';
                    $subscription->status = 'cancelled';
                    $subscription->save();
                }
            }
        }

        //paid packages - free trial
        if ($package->subscription_options == 'paid' && $data['free_trial'] == 'yes') {
            $subscription_status = 'free-trial';
            $free_trial = 'yes';
            $subscription_trial_end = \Carbon\Carbon::now()->addDays($data['free_trial_days'])->format('Y-m-d');
            $subscription_amount = ($data['billing_cycle'] == 'monthly') ? $package->amount_monthly : $package->amount_yearly;
            $subscription_date_started = null;
        }

        //paid packages - free trial
        if ($package->subscription_options == 'paid' && $data['free_trial'] == 'no') {
            $subscription_status = 'awaiting-payment';
            $free_trial = 'no';
            $subscription_trial_end = null;
            $subscription_amount = ($data['billing_cycle'] == 'monthly') ? $package->amount_monthly : $package->amount_yearly;
            $subscription_date_started = null;
        }

        //free packages
        if ($package->subscription_options == 'free') {
            $subscription_status = 'active';
            $free_trial = 'no';
            $subscription_trial_end = null;
            $subscription_amount = 0;
            $subscription_date_started = now();
        }

        $subscription = new Subscription();
        $subscription->setConnection('landlord');
        $subscription->added_by  = auth()->id();
        $subscription->unique_id = str_unique();
        $subscription->customer_id = $customer->id;
        $subscription->type = $package->subscription_options;
        $subscription->payment_method = $data['billing_type'];
        $subscription->amount = $subscription_amount;
        $subscription->trial_end = $subscription_trial_end;
        $subscription->date_started = $subscription_date_started;
        $subscription->package_id = $package->id;
        $subscription->status = $subscription_status;
        $subscription->gateway_billing_cycle = $data['billing_cycle'];
        $subscription->save();

        //change customer status
        $customer->status = $subscription_status;
        $customer->save();

        Log::info("changing customers subscription plan - completed", ['process' => '[change-customers-subscription-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'subscription' => $subscription]);

        return $subscription;

    }

    public function paySubscription(Request $request,$id)
    {

        if (!$payment_data = $this->checkoutRepo->getPaymentData($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }
        $subscription = $payment_data['subscription'];
        $package = $payment_data['package'];
        $settings = $payment_data['settings'];

        //payment payload
        $data = [
            'package' => $package,
            'package_id' => $package->id,
            'stripe_secret_key' => $settings->stripe_secret_key,
            'currency' => $settings->system_currency_code,
            'tenant_id' => config('system.saas_tenant_id'),
            'subscription_id' => $subscription->id,
            'billing_cycle' => $subscription->gateway_billing_cycle,
            'cancel_url' => route('settings-billing.show-packages'),
        ];

        //create a new stripe session
        if (!$checkout_session_id = $this->striperepo->initiateSubscriptionPayment($data)) {
            Log::error("unable to create a stripe checkout session", ['process' => '[permissions]', config('stripe-paynow-button'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'tenant_id' => config('system.settings_saas_tenant_id')]);
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //update subscription with stripe data
        $subscription->gateway_plan_id = ($subscription->gateway_billing_cycle == 'monthly') ? $package->gateway_stripe_product_monthly : $package->gateway_stripe_product_yearly;
        $subscription->gateway_price_id = ($subscription->gateway_billing_cycle == 'monthly') ? $package->gateway_stripe_price_monthly : $package->gateway_stripe_price_yearly;
        $subscription->save();

        return [
            'checkout_session_id' => $checkout_session_id,
            'stripe_public_key' => $settings->stripe_public_key,
            'landlord_settings' => $settings,
        ];


    }


}
