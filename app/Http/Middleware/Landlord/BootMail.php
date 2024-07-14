<?php

/** ---------------------------------------------------------------------------------------------------------------
 * The purpose of this middleware it to set fallback config values
 * for older versions of Grow CRM that are upgrading. Reason being that new
 * values in the file /config/settings.php will not exist for them (as settings files in not included in updates)
 *
 *
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 * @revised    9 July 2021
 *--------------------------------------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Landlord;
use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Setting;
use Closure;

class BootMail {

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $settings = Setting::Where('id', 'default')->first();
        $email_signature = '';
        $email_footer = '';
        if ($template = EmailTemplate::Where('name', 'Email Signature')->first()) {
            $email_signature = $template->body;
        }
        if ($template = EmailTemplate::Where('name', 'Email Footer')->first()) {
            $email_footer = $template->body;
        }

        config([
            'mail.driver' => $settings->email_server_type,
            'mail.host' => $settings->email_smtp_host,
            'mail.port' => $settings->email_smtp_port,
            'mail.username' => $settings->email_smtp_username,
            'mail.password' => $settings->email_smtp_password,
            'mail.encryption' => ($settings->email_smtp_encryption == 'none') ? '' : $settings->email_smtp_encryption,
            'mail.data' => [
                'company_name' => config('system.company_name'),
                'todays_date' => runtimeDate(date('Y-m-d')),
                'email_signature' => $email_signature,
                'email_footer' => $email_footer,
                'dashboard_url' => url('/'),
            ],
        ]);

        return $next($request);

    }

}
