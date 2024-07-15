<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles precheck processes for general processes
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\General;
use App\Http\Responses\Account\Notices\NewPaymentResponse;
use App\Http\Responses\Account\Notices\SubscriptionCancelledFailedResponse;
use App\Models\Setting;
use Auth;
use Cache;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class General {

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $this->lastSeen();
        $this->setLanguage();
        $this->setStripe();
        //check account status

        if (auth('web')->check()) {

            if (empty(auth()->user()->email_verified_at)) {
                if (request()->ajax()) {
                    abort(409, __('lang.email_verification_required'));
                    return $next($request);
                } else {
                    request()->session()->flash('error-notification-long', __('lang.email_verification_required'));
                    if(in_array(request()->route()->getName(),['verification.notice','verification.verify','verification.resend'])) {
                        return $next($request);
                    }
                    return redirect('email/verify');

                }
            }

            if (!auth()->user()->status) {
                if (request()->ajax()) {
                    abort(409, __('lang.account_has_been_deactivated'));
                    return $next($request);
                } else {
                    request()->session()->flash('error-notification-long', __('lang.account_has_been_deactivated'));
                    auth()->logout();
                    return redirect(route('account-status', ['status' => 'deactivated']));
                }
            }
        }

        // if(in_array(request()->route()->getName(),['settings-billing.packages','settings-billing.change-package','settings-billing.payment-required','settings-billing.subscription-cancelled-failed','settings-billing.pay'])) {
        //     return $next($request);
        // }

        // if(Auth::check()){
        //     return $this->isPaymentCompleted($next,$request);
        // }
        return $next($request);
    }


    /**
     * apply database prefilters for all client users
     * @return void
     */
    private function lastSeen() {
        if (auth()->check()) {
            $user = auth()->user();
            $user->last_seen_at = \Carbon\Carbon::now();
            $user->last_ip_address = request()->ip();
            $user->save();
        }
        if (auth()->check()) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(3);
            Cache::put('user-is-online-' . auth()->user()->id, true, $expiresAt);
        }
    }

    /**
     * set the language to be used by the app
     * @return void
     */
    private function setLanguage() {

        //set default system language first
        $lang = config('system.system_language_default');
        if (file_exists(resource_path("lang/$lang"))) {
            request()->merge([
                'system_language' => $lang,
            ]);
            \App::setLocale($lang);
        } else {
            request()->merge([
                'system_language' => 'english',
            ]);
            \App::setLocale('english');
        }
        if (auth()->check() && config('system.system_language_allow_users_to_change') == 'yes') {
            $lang = auth()->user()->pref_language??'en';
            if (file_exists(resource_path("lang/$lang"))) {
                \App::setLocale($lang);
            }
        }

        request()->merge([
            'system_languages' => ['en'],
        ]);
    }

    private function isPaymentCompleted($next,$request)
    {
        if (!$settings = Setting::Where('id', 'default')->first()) {
            $settings = Setting::Where('id', 1)->first();
        }
        //subscription is awaiting payment
        if ($settings->saas_status == 'awaiting-payment') {
            return redirect("settings/billing/{$settings->saas_package_id}/payment-required");
        }elseif (!$subscription = DB::connection('landlord')
            ->table('subscriptions')
            ->where('customer_id', $settings->saas_tenant_id)
            ->where('archived', 'yes')
            ->first()) {
            Log::info("unable to fetch the tenants subscription from landlord database - record not found", ['process' => '[tenant-notices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'tenant_id' => $settings->saas_tenant_id]);
            return redirect('settings/billing/packages');
        }elseif (!$landlord_settings = DB::connection('landlord')
            ->table('settings')
            ->where('settings_id', 'default')
            ->first()) {
            Log::critical("unable to fetch the landlord settimgs table", ['process' => '[tenant-notices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'tenant_id' => $settings->saas_tenant_id]);
            abort(409, __('lang.error_message_with_code') . config('app.debug_ref'));
        }elseif (!$package = DB::connection('landlord')
            ->table('packages')
            ->where('package_id', $settings->saas_package_id)
            ->first()) {
            Log::critical("unable to fetch the customer plan from the landlord database", ['process' => '[tenant-notices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'plan_id' => $settings->saas_package_id]);
            abort(409, __('lang.error_message_with_code') . config('app.debug_ref'));
        }elseif ($settings->saas_status == 'cancelled') {
            $payload = [
                'page' => $this->pageSettings('index'),
                'subscription' => $subscription,
                'package' => $package,
                'landlord_settings' => $landlord_settings,
            ];
            return new SubscriptionCancelledFailedResponse($payload);
        }
        return $next($request);

    }

    private function setStripe()
    {
        if (!$settings = Setting::select(['stripe_public_key','stripe_secret_key','stripe_webhooks_key','stripe_status'])->Where('id', 'default')->first()) {
            $settings = Setting::select(['stripe_public_key','stripe_secret_key','stripe_webhooks_key','stripe_status'])->Where('id', 1)->first();
        }
        if ($settings->stripe_status == 'enabled') {
            config(['services.stripe.key' => $settings->stripe_public_key]);
            config(['services.stripe.secret' => $settings->stripe_secret_key]);
            config(['services.stripe.webhook_secret_key' => $settings->stripe_webhooks_key]);
        }
    }

}
