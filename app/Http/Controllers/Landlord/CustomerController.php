<?php

namespace App\Http\Controllers\Landlord;

use App\DataTables\Landlord\CustomerDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Landlord\Customers\SetActiveValidation;
use App\Http\Requests\Landlord\Customers\CreateRequest;
use App\Http\Requests\Landlord\Customers\UpdatePasswordValidation;
use App\Http\Requests\Landlord\Customers\UpdateRequest;
use App\Mail\Landlord\Customer\NewCustomerWelcome;
use App\Models\Landlord\Package;
use App\Models\Landlord\Schedule;
use App\Models\Landlord\Subscription;
use App\Repositories\Landlord\CreateTenantRepository;
use App\Repositories\Landlord\SubscriptionsRepository;
use App\Repositories\Landlord\TenantsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Spatie\Multitenancy\Models\Tenant;

class CustomerController extends AppBaseController
{
    public function __construct( protected TenantsRepository $tenantsRepo,
        protected SubscriptionsRepository $subscriptionRepo
    ) {
        parent::__construct();
        $this->middleware('auth');

    }

    public function index(Request $request) {
        if ($request->ajax()) {
            $columns = [
                0 => 'id',
                1 => 'name',
                2 => 'email',
                3 => 'created_at',
                4 => 'domain',
                5 => 'name',
                6 => 'type',
                7 => 'status',
                8 => 'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = CustomerDataTable::totalRecords();
            $customers = CustomerDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = CustomerDataTable::totalFilteredRecords($search);
            $data = CustomerDataTable::populateRecords($customers);

            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);
        }
        return view('landlord/customers/index');
    }

    public function show($id) {
        abort(403,'Not implemented.Coming Soon!');
        if (\App\Models\Landlord\Tenant::where('id', $id)->doesntExist()) {
            abort(404);
        }

        $customers = $this->tenantsRepo->search($id);
        $customer = $customers->first();

        //get customers subscription if there is one
        if ($subscription = Subscription::Where('customer_id', $id)
            ->where('archived', 'no')
            ->first()) {
            config(['visibility.has_subscription' => true]);
            config(['status' => $subscription->subscription_status]);
        } else {
            $subscription = [];
        }

    }

    public function create() {

        $activePackages = Package::active()->select(['id','name','amount_monthly','amount_yearly'])->get();
        if ($activePackages->isNotEmpty()){
            foreach ($activePackages as $package){
                $packages["{$package->id}_monthly"] = $package->name.' ('.formatAmountWithCurrency($package->amount_monthly).' / Month)';
                $packages["{$package->id}_yearly"]  = $package->name.' ('.formatAmountWithCurrency($package->amount_yearly).' /Year)';
            }
        }else{
            $packages = [];
        }
        return view('landlord.customers.create', compact('packages'));
    }

    public function store(CreateRequest $request, CreateTenantRepository $createTenantRepo) {
        $trial_end = null;
        $date_started = null;
        list($planId,$planType) = explode('_',$request->plan);

        $package = Package::Where('id', $planId)->first();

        //DISABLED:: free packages  free packages are not available
//        if ($package->subscription_options == 'free') {
//            $status = 'active';
//            $amount = 0;
//            $date_started = now();
//        }

        //NOTE::general settings for paid subscriptions always paid
        if ($package->subscription_options == 'paid' || true) {
            if ($planType == 'monthly') {
                $amount = $package->amount_monthly;
            } else {
                $amount = $package->amount_yearly;
            }
        }

        //DISABLED:: paid packages - free trial
//        if ($package->subscription_options == 'paid' && request('free_trial') == 'yes') {
//            $status = 'free-trial';
//            $free_trial = 'yes';
//            $trial_end = \Carbon\Carbon::now()->addDays(request('free_trial_days'))->format('Y-m-d');
//        }

        //paid packages - no free trial Note:: &&  request('free_trial') == 'no' can be added when trail will be enabled
        if ($package->subscription_options == 'paid' ) {
            $status = 'awaiting-payment';
        }

        //generate a customer password
        $password = Str::password(20);
        $hashedPassword = App::environment(['production']) ? Hash::make($password) : Hash::make('abcd1234');

        //create tenant
        $customer = new \App\Models\Landlord\Tenant();
        $customer->domain = strtolower(request('account_name') . '.' . config('system.base_domain'));
        $customer->subdomain = strtolower(request('account_name'));
        $customer->added_by = auth()->id();
        $customer->name = request('full_name');
        $customer->email = request('email_address');
        $customer->status = $status;
        $customer->email_local_email = strtolower(request('account_name') . '@' . config('system.email_domain'));
        $customer->email_forwarding_email = request('email_address');
        $customer->email_config_type = 'local';
        $customer->email_config_status = 'pending';
        $customer->password = $hashedPassword; //(hashed password)
        $customer->updating_current_version = config('system.version');
        $customer->save();

        //temp authentication key
        $auth_key = Str::random(30);
        //create tenant database
        if (!$createTenantRepo->createTenant($customer, $package, $auth_key)) {
            $customer->delete();
            abort(409, __('lang.request_failed_see_logs'));
        }

        //account url
        $account_url = "https://{$customer->domain}/auth?id_key={$auth_key}";

        //create subscription
        $subscription = new Subscription();
        $subscription->added_by  = auth()->id();
        $subscription->customer_id = $customer->id;
        $subscription->unique_id = str_unique();
        $subscription->type = $package->subscription_options;
        $subscription->amount = $amount;
        $subscription->trial_end = $trial_end;
        $subscription->date_started = $date_started;
        $subscription->package_id = $package->id;
        $subscription->payment_method = 'automatic';
        $subscription->status = $status;
        $subscription->gateway_billing_cycle = $planType;
        $subscription->save();

        /** ----------------------------------------------
         * send email to customer & Admin
         * ----------------------------------------------*/
        if (request('send_welcome_email') == 'yes') {
            $data = [
                'subscription_type' => $subscription->subscription_type,
                'account_name' => $customer->domain,
                'customer_name' => $customer->tenant_name,
                'customer_id' => $customer->tenant_id,
                'account_url' => $account_url,
                'password' => $password,
            ];
            //customer
            $mail = new NewCustomerWelcome($customer, $data, $package);
            $mail->build();
        }
        Flash::success('Tenant Customer created successfully.');
        return redirect(route('landlord.customers.index'));
    }


    public function edit($id) {
        $customer = \App\Models\Landlord\Tenant::Where('id', $id)->select(['id','name','email','subdomain'])->firstOrFail();
        return view('landlord.customers.edit', compact('customer'));
    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id) {
        $customer = \App\Models\Landlord\Tenant::Where('id', $id)->first();
        //store record
        $customer->tenant_name = $request->full_name;
        $customer->tenant_email = $request->email_address;
        $customer->domain = strtolower($request->account_name) . '.' . config('system.base_domain');
        $customer->subdomain = strtolower($request->account_name);
        $customer->save();

        Flash::success('Tenant Customer updated successfully.');
        return redirect(route('landlord.customers.index'));
    }

    /**
     * Show the customer subscription details
     * @return blade view | ajax view
     */
    public function showSubscription($id) {

        //get the customers subscription
        if ($subscription = Subscription::Where('subscription_customerid', $id)
            ->where('subscription_archived', 'no')
            ->leftJoin('packages', 'packages.package_id', '=', 'subscriptions.subscription_package_id')
            ->first()) {
            config(['visibility.has_subscription' => true]);
        } else {
            $subscription = [];
            config(['visibility.has_subscription' => false]);
        }

        //page
        $html = view('landlord/subscriptions/details', compact('subscription'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#dynamic-content-container',
            'action' => 'replace',
            'value' => $html,
        ];

        $jsondata['dom_visibility'][] = [
            'selector' => '.list-page-actions-containers',
            'action' => 'hide',
        ];
        $jsondata['dom_visibility'][] = [
            'selector' => '#list-page-actions-container-customer',
            'action' => 'show',
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * Show the customer subscription details
     * @return blade view | ajax view
     */
    public function showEmailSettings($id) {

        //get the customers subscription
        if (!$customer = \App\Models\Landlord\Tenant::Where('tenant_id', $id)->first()) {
            abort(404);
        }

        $jsondata = [];

        //page view
        if (request('source') == 'page') {
            $html = view('landlord/customer/email/email-settings', compact('customer'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#dynamic-content-container',
                'action' => 'replace',
                'value' => $html,
            ];

            $jsondata['dom_visibility'][] = [
                'selector' => '.list-page-actions-containers',
                'action' => 'hide',
            ];
        }

        //list view (modal)
        if (request('source') == 'list') {
            $html = view('landlord/customer/email/email-settings', compact('customer'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#commonModalBody',
                'action' => 'replace',
                'value' => $html,
            ];
        }

        //render
        return response()->json($jsondata);

    }

    /**
     * mark that the custoners email has been done
     *
     * @return \Illuminate\Http\Response
     */
    public function markEmailSettingsDone($id) {

        //get the item
        if (!$customer = \App\Models\Landlord\Tenant::Where('tenant_id', $id)->first()) {
            abort(404);
        }

        //update customer
        $customer->tenant_email_config_status = 'completed';
        $customer->save();

        //count pending
        $count = \App\Models\Landlord\Tenant::where('tenant_email_config_status', 'pending')->count();

        if ($count == 0) {
            $jsondata['dom_visibility'][] = [
                'selector' => "#menu_tenant_email_config_status",
                'action' => 'hide',
            ];
        }

        $jsondata['dom_visibility'][] = [
            'selector' => ".email_settings_pending_$id",
            'action' => 'hide',
        ];
        $jsondata['dom_visibility'][] = [
            'selector' => "#email_settings_completed_$id",
            'action' => 'show',
        ];

        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        //notice error
        $jsondata['notification'] = [
            'type' => 'success',
            'value' => __('lang.request_has_been_completed'),
        ];

        //ajax response
        return response()->json($jsondata);

    }

    /**
     * delete a record
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriptionsRepository $subscriptionsRepo, $id) {

        $customer = \App\Models\Landlord\Tenant::Where('id', $id)->first();

        //schedule for cronjob - delete database
        if (!empty($customer->database)){
            $schedule = new Schedule();
            $schedule->type = 'delete-database';
            $schedule->payload_1 = $customer->database;
            $schedule->save();
        }

        //delete record
        $customer->delete();

        //delete subscription locally and at schedule for deleting at the payment gateway
        if ($subscription = Subscription::Where('customer_id', $id)->first()) {
            if ($subscription->status == 'active' || $subscription->status == 'failed') {
                if ($subscription->gateway_id != '' && $subscription->gateway_name != '') {
                    $scheduled = new Schedule();
                    $scheduled->gateway = $subscription->gateway_name;
                    $scheduled->type = 'cancel-subscription';
                    $scheduled->payload_1 = $subscription->gateway_id;
                    $scheduled->payload_2 = $subscription->checkout_reference_2;
                    $scheduled->payload_3 = $subscription->checkout_reference_3;
                    $scheduled->payload_4 = $subscription->checkout_reference_4;
                    $scheduled->payload_5 = $subscription->checkout_reference_5;
                    $scheduled->save();
                }
            }
            $subscription->delete();
        }

        //delete any other subscriptions
        Subscription::Where('customer_id', $id)->delete();
        return redirect(route('landlord.customers.index'));
    }

    /**
     * Show evet timeline
     * @return blade view | ajax view
     */
    public function events($id) {

        //get events
        $events = \App\Models\Landlord\Event::Where('event_customer_id', $id)
            ->leftJoin("users", "users.id", "=", "events.event_creatorid")
            ->leftJoin("tenants", "tenants.tenant_id", "=", "events.event_customer_id")->get();

        //page
        $html = view('landlord/events/event', compact('events'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#customer-content-container',
            'action' => 'replace',
            'value' => $html,
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function editPassword() {

        //page
        $html = view('landlord/customers/modal/update-password')->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //postrun
        $jsondata['postrun_functions'][] = [
            'value' => 'NXCustomerUpdatePassword',
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordValidation $request, $id) {

        //[demo check]
        if (config('app.application_demo_mode') && in_array($id, [1, 2, 3])) {
            abort(409, 'Demo Mode: You cannot delete the main demo accounts. You can create new ones for testing');
        }

        //get the customer
        if (!$customer = Tenant::Where('tenant_id', $id)->first()) {
            abort(404);
        }

        //reset
        Tenant::forgetCurrent();

        //get the customer from landlord db
        try {
            //swicth to this tenants DB
            $customer->makeCurrent();

            //update teh default users password
            if ($user = \App\Models\User::on('tenant')->Where('id', 1)->first()) {
                $user->password = Hash::make(request('password'));
                $user->save();
            }

        } catch (Exception $e) {
            abort(409, $e->getMessage());
        }


        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        //notice error
        $jsondata['notification'] = [
            'type' => 'success',
            'value' => __('lang.request_has_been_completed'),
        ];

        $jsondata['skip_dom_reset'] = true;

        //ajax response
        return response()->json($jsondata);

    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function setStatusActive() {

        //page
        $html = view('landlord/customers/modal/set-active')->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //postrun
        $jsondata['postrun_functions'][] = [
            'value' => 'NXCustomerUpdatePassword',
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatusActive(SetActiveValidation $request, $id) {

        //[demo check]
        if (config('app.application_demo_mode') && in_array($id, [1, 2, 3])) {
            abort(409, 'Demo Mode: You cannot update the main demo accounts. You can create new ones for testing');
        }

        //get the customer
        if (!$customer = Tenant::Where('tenant_id', $id)->first()) {
            abort(404);
        }

        //get the subscription
        if (!$subscription = \App\Models\Subscription::Where('subscription_customerid', $id)->first()) {
            abort(409, __('lang.no_subscription_exists_for_customer'));
        }

        //mark as subscription as active
        $subscription->subscription_status = 'active';
        $subscription->subscription_date_next_renewal = request('expiry_date');
        $subscription->save();

        //mark as customer as active
        //reset existing account owner
        \App\Models\Landlord\Tenant::where('tenant_id', $id)
            ->update(['tenant_status' => 'active']);

        //reset
        Tenant::forgetCurrent();

        //get the customer from landlord db
        try {
            //swicth to this tenants DB
            $customer->makeCurrent();

            //update teh default users password
            if ($settings = \App\Models\Settings::on('tenant')->Where('settings_id', 1)->first()) {
                $settings->settings_saas_status = 'active';
                $settings->save();
            }

        } catch (Exception $e) {
            abort(409, $e->getMessage());
        }


        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        $jsondata['redirect_url'] = url("app-admin/customers/$id");

        request()->session()->flash('success-notification-long', __('lang.request_has_been_completed'));

        //ajax response
        return response()->json($jsondata);

    }

    /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function syncAccount() {

        //page
        $html = view('landlord/customers/modal/sync-account')->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * Sync various items of the tenants database, with the landlord database
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSyncAccount($id) {

        //get the customer
        if (!$customer = Tenant::Where('tenant_id', $id)->first()) {
            abort(404);
        }

        //get tenant
        $tenants = $this->tenantsRepo->search($id);
        $tenant = $tenants->first();

        //reset
        Tenant::forgetCurrent();

        //get the customer from landlord db
        try {
            //swicth to this tenants DB
            $customer->makeCurrent();

            //update teh default users password
            if ($settings = \App\Models\Settings::on('tenant')->Where('settings_id', 1)->first()) {
                $settings->settings_saas_status = ($tenant->subscription_status != '') ? $tenant->subscription_status : 'cancelled';
                $settings->settings_saas_tenant_id = $id;
                $settings->settings_saas_package_id = (is_numeric($tenant->id)) ? $tenant->id : null;
                $settings->settings_saas_package_limits_clients = (is_numeric($tenant->limits_clients)) ? $tenant->limits_clients : 0;
                $settings->settings_saas_package_limits_team = (is_numeric($tenant->limits_team)) ? $tenant->limits_team : 0;
                $settings->settings_saas_package_limits_projects = (is_numeric($tenant->limits_projects)) ? $tenant->limits_projects : 0;
                $settings->settings_modules_projects = ($tenant->module_projects == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_tasks = ($tenant->module_tasks == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_invoices = ($tenant->module_invoices == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_leads = ($tenant->module_leads == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_knowledgebase = ($tenant->module_knowledgebase == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_estimates = ($tenant->module_estimates == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_expenses = ($tenant->module_expense == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_subscriptions = ($tenant->module_subscriptions == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_tickets = ($tenant->module_tickets == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_timetracking = ($tenant->module_timetracking == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_reminders = ($tenant->module_reminders == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_proposals = ($tenant->module_proposals == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_contracts = ($tenant->module_contracts == 'yes') ? 'enabled' : 'disabled';
                $settings->settings_modules_messages = ($tenant->module_messages == 'yes') ? 'enabled' : 'disabled';
                $settings->save();
            }

        } catch (Exception $e) {
            abort(409, $e->getMessage());
        }


        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        $jsondata['redirect_url'] = url("app-admin/customers/$id");

        request()->session()->flash('success-notification', __('lang.request_has_been_completed'));

        //ajax response
        return response()->json($jsondata);

    }

    /**
     * login as the customer
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function LoginAsCustomer($id) {

        //get the customer from landlord db
        if (!$customer = Tenant::Where('tenant_id', $id)->first()) {
            abort(404);
        }

        //reset
        Tenant::forgetCurrent();

        try {
            //swicth to this tenants DB
            $customer->makeCurrent();

            $key = str_unique();

            //add the onetime login key to the tenant database
            \App\Models\Settings::on('tenant')->where('settings_id', 1)
                ->update(['settings_saas_onetimelogin_key' => $key]);

        } catch (Exception $e) {
            $error = $e->getMessage();
            Log::info("logging in as the customer failed - error: $error", ['process' => '[authenticate-login-as-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            abort(409, __('lang.error_check_logs_for_details'));
        }

        //reset
        Tenant::forgetCurrent();

        //redirect to the customers url and auto login
        $url = 'https://' . $customer->domain . "/access?id_key=$key";
        return redirect($url);

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.customers'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'meta_title' => __('lang.customers'),
            'heading' => __('lang.customers'),
            'page' => 'customers',
            'mainmenu_customers' => 'active',
        ];

        //return
        return $page;
    }
}
