<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles precheck processes for general processes
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\General;
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

        //check account status
        if (auth('web')->check()) {
//            if (!auth()->user()->status) {
//                if (request()->ajax()) {
//                    abort(409, __('lang.account_has_been_suspended'));
//                    return $next($request);
//                } else {
//                    request()->session()->flash('error-notification-long', __('lang.account_has_been_suspended'));
//                    auth()->logout();
//                    abort(409, __('lang.account_has_been_suspended'));
//                    return $next($request);
//                }
//            }
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

}
