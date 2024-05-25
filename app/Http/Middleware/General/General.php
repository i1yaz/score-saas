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
use Cache;
use Closure;

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
        $this->isPaymentCompleted();
        //check account status
        if (auth('web')->check()) {

            if (empty(auth()->user()->email_verified_at)) {
                if (request()->ajax()) {
                    abort(409, __('lang.email_verification_required'));
                    return $next($request);
                } else {
                    request()->session()->flash('error-notification-long', __('lang.email_verification_required'));
                    auth()->logout();
                    abort(409, __('lang.email_verification_required'));
                    return $next($request);
                }
            }

            if (!auth()->user()->status) {
                if (request()->ajax()) {
                    abort(409, __('lang.account_has_been_deactivated'));
                    return $next($request);
                } else {
                    request()->session()->flash('error-notification-long', __('lang.account_has_been_deactivated'));
                    auth()->logout();
                    abort(409, __('lang.account_has_been_deactivated'));
                    return $next($request);
                }
            }
        }

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

    private function isPaymentCompleted()
    {
        $settings = Setting::Where('settings_id', 1)->first();
        if (!$subscription = DB::connection('landlord')
            ->table('subscriptions')
            ->where('subscription_customerid', $settings->settings_saas_tenant_id)
            ->where('subscription_archived', 'no')
            ->first()) {
            Log::info("unable to fetch the tenants subscription from landlord database - record not found", ['process' => '[tenant-notices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'tenant_id' => $settings->settings_saas_tenant_id]);
            if (request()->ajax()) {
                return response()->json(array(
                    'redirect_url' => '/app/settings/account/packages',
                ));
            } else {
                return redirect('/app/settings/account/packages');
            }
        }

        //get admin payment gateway settings
        if (!$landlord_settings = DB::connection('landlord')
            ->table('settings')
            ->where('settings_id', 'default')
            ->first()) {
            Log::critical("unable to fetch the landlord settimgs table", ['process' => '[tenant-notices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'tenant_id' => $settings->settings_saas_tenant_id]);
            abort(409, __('lang.error_message_with_code') . config('app.debug_ref'));
        }

        //get customer package
        if (!$package = DB::connection('landlord')
            ->table('packages')
            ->where('package_id', $settings->settings_saas_package_id)
            ->first()) {
            Log::critical("unable to fetch the customer plan from the landlord database", ['process' => '[tenant-notices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'plan_id' => $settings->settings_saas_package_id]);
            abort(409, __('lang.error_message_with_code') . config('app.debug_ref'));
        }

        //subscription is awaiting payment
        if ($settings->settings_saas_status == 'awaiting-payment') {
            $payload = [
                'page' => $this->pageSettings('index'),
                'subscription' => $subscription,
                'package' => $package,
                'landlord_settings' => $landlord_settings,
            ];
            return new NewPaymentResponse($payload);
        }

        //subscription is awaiting payment
        if ($settings->settings_saas_status == 'cancelled') {
            $payload = [
                'page' => $this->pageSettings('index'),
                'subscription' => $subscription,
                'package' => $package,
                'landlord_settings' => $landlord_settings,
            ];
            return new SubscriptionCancelledFailedResponse($payload);
        }

    }

}
