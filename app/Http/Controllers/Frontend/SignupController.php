<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Landlord\CreateAccountRequest;
use App\Http\Requests\Landlord\Signup\CreateAccount;
use App\Models\Landlord\Frontend;
use App\Models\Landlord\Package;
use App\Repositories\Landlord\CreateTenantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function index() {

        //get packages
        $packages = Package::active()->get();

        $page = $this->pageSettings('index');

        //main menu
        $main_menu = Frontend::Where('group', 'main-menu')->orderBy('name', 'asc')->get();

        //get the item
        $section = Frontend::Where('name', 'page-signup')->first();

        return view('frontend/signup/page', compact('page', 'packages', 'main_menu', 'section'));

    }
    public function createAccount(CreateAccountRequest $request, CreateTenantRepository $createTenantRepo) {
        $free_trial = 'no';
        $subscription_trial_end = null;
        $subscription_date_started = null;
        //validate terms
        if (config('system.terms_of_service_status') == 'enabled') {
            if (request('signup_agree_terms') != 'on') {
                abort(409, __('lang.agree_to_terms_of_service'));
            }
        }

        //correct the plain id by removing extra strings
        $plan_id = str_replace(['monthly_', 'yearly_', 'free_'], '', request('plan'));

        //get the package
        if (!$package = Package::Where('id', $plan_id)->first()) {
            abort(409, __('lang.package_not_found'));
        }

        //free packages
        if ($package->subscription_options == 'free') {
            $status = 'active';
            $subscription_amount = 0;
            $subscription_date_started = now();
        }

        //general settings for paid subscriptions
        if ($package->subscription_options == 'paid') {
            if (request('billing_cycle') == 'monthly') {
                $subscription_amount = $package->amount_monthly;
            } else {
                $subscription_amount = $package->amount_yearly;
            }
        }

        //paid packages - free trial
        if ($package->subscription_options == 'paid' && config('system.free_trial') == 'yes') {
            $status = 'free-trial';
            $free_trial = 'yes';
            $subscription_trial_end = \Carbon\Carbon::now()->addDays(config('system.free_trial_days'))->format('Y-m-d');
        }

        //paid packages - free trial
        if ($package->subscription_options == 'paid' && config('system.free_trial') == 'no') {
            $status = 'awaiting-payment';
        }

        //create tenant
        $customer = new \App\Models\Landlord\Tenant();
        $customer->domain = strtolower(request('account_name') . '.' . config('system.base_domain'));
        $customer->subdomain = strtolower(request('account_name'));
        $customer->tenant_creatorid = 0;
        $customer->tenant_name = request('full_name');
        $customer->tenant_email = request('email_address');
        $customer->tenant_status = $status;
        $customer->tenant_email_local_email = strtolower(request('account_name') . '@' . config('system.email_domain'));
        $customer->tenant_email_forwarding_email = request('email_address');
        $customer->tenant_email_config_type = 'local';
        $customer->tenant_email_config_status = 'pending';
        $customer->tenant_password = bcrypt(request('password'));
        $customer->tenant_updating_current_version = config('system.version');
        $customer->save();

        //temp authentication key
        $auth_key = Str::random(30);

        //create tenant database
        if (!$createtenantrepo->createTenant($customer, $package, $auth_key)) {
            $customer->delete();
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //redirect url
        $protocol = (request()->secure()) ? 'https://' : 'http://';
        $account_url = $protocol . $customer->domain . "/auth?id_key=$auth_key";

        //create subscription
        $subscription = new \App\Models\Landlord\Subscription();
        $subscription->subscription_uniqueid = str_unique();
        $subscription->subscription_creatorid = 0;
        $subscription->subscription_customerid = $customer->tenant_id;
        $subscription->subscription_type = $package->subscription_options;
        $subscription->subscription_amount = $subscription_amount;
        $subscription->subscription_trial_end = $subscription_trial_end;
        $subscription->subscription_date_started = $subscription_date_started;
        $subscription->subscription_id = $package->id;
        $subscription->subscription_status = $status;
        $subscription->subscription_gateway_billing_cycle = request('billing_cycle');
        $subscription->save();

        /** ----------------------------------------------
         * record event
         * ----------------------------------------------*/
        $event = new \App\Models\Landlord\Event();
        $event->event_creatorid = $customer->tenant_id;
        $event->event_type = 'account-created';
        $event->event_creator_type = 'customer';
        $event->event_item_id = $customer->tenant_id;
        $event->event_payload_1 = $customer->tenant_name;
        $event->event_payload_2 = $package->name;
        $event->event_payload_3 = '';
        $event->save();

        /** ----------------------------------------------
         * send email to customer & Admin
         * ----------------------------------------------*/
        $data = [
            'subscription_type' => $subscription->subscription_type,
            'account_name' => $customer->domain,
            'customer_name' => $customer->tenant_name,
            'customer_id' => $customer->tenant_id,
            'account_url' => $account_url,
            'password' => __('lang.as_set_during_signup'),
        ];

        //customer
        $mail = new \App\Mail\Landlord\Customer\NewCustomerWelcome($customer, $data, $package);
        $mail->build();

        //admin users
        if ($admins = \App\Models\User::On('landlord')->Where('type', 'admin')->get()) {
            foreach ($admins as $user) {
                $mail = new \App\Mail\Landlord\Admin\NewCustomerSignUp($user, $data, $package);
                $mail->build();
            }
        }

        //redirect
        $jsondata['redirect_url'] = $account_url;

        //ajax response
        return response()->json($jsondata);

    }
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [

        ];

        //return
        return $page;
    }
}
