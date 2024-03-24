<?php

/** ---------------------------------------------------------------------------------------------------------------
 * [NEXTLOOPS]
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
use App\Models\Landlord\Setting;
use App\Models\Landlord\Tenant;
use Closure;

class BootSystem {

    public $settings;

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {


        //set up the landlord database
        $this->setupDatabase();

        //default settings
        $this->systemSettings();

        return $next($request);

    }


    /**
     * save system settings
     */
    public function setupDatabase(): void
    {

        //set the landlord database connection
        config()->set('database.default', 'landlord');

    }

    /**
     * save  system settings
     */
    public function systemSettings(): void
    {

        //get the general settings
        $settings = Setting::Where('id', 'default')->first();
        $this->settings = $settings;

        //set timezone
        date_default_timezone_set($settings->system_timezone);

        //currency symbol position setting
        if ($settings->system_currency_position == 'left') {
            $settings['currency_symbol_left'] = $settings->system_currency_symbol;
            $settings['currency_symbol_right'] = '';
        } else {
            $settings['currency_symbol_right'] = $settings->system_currency_symbol;
            $settings['currency_symbol_left'] = '';
        }

        //cronjob path
        $settings['cronjob_path'] = '/usr/local/bin/php ' . BASE_DIR . '/application/artisan schedule:run >> /dev/null 2>&1';


        //javascript file versioning to avoid caching when making updates
        $settings['versioning'] = $settings->system_javascript_versioning;

        //count tenant with pending email settings
        $settings['count_email_config_status'] = Tenant::where('email_config_status', 'pending')->count();

        //save to config
        config(['system' => $this->settings]);

        //cronjob path
        $cron_path = '/usr/local/bin/php ' . BASE_DIR . '/artisan schedule:run >> /dev/null 2>&1';
        $cron_path = str_replace('\\', '/', $cron_path);
        $cron_path_2 = '/usr/local/bin/php ' . BASE_DIR . '/artisan tenants:artisan schedule:run >> /dev/null 2>&1';
        $cron_path_2 = str_replace('\\', '/', $cron_path_2);

        config([
            'cronjob_path' => $cron_path,
            'cronjob_path_2' => $cron_path_2,
        ]);

    }
}
