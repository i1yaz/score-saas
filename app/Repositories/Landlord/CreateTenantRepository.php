<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\Defaults;
use App\Models\Landlord\Tenant;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateTenantRepository
{
    public function __construct(private DatabaseRepository $databaseRepository)
    {
    }

    public function createTenant($customer = [], $package = [], $auth_key = '') {

        //create database
        if ($database_name = $this->databaseRepository->createDatabase()) {
            Tenant::where('id', $customer->id)
                ->update([
                    'database' => $database_name,
                ]);
        } else {
            return false;
        }

        //populate the database
        if (!$this->configureDB($customer, $package, $auth_key)) {
            return false;
        }

        //return new database information
        return true;

    }
    public function configureDB($customer = [], $package = [], $auth_key = '') {
        $tenant_id = $customer->id;
        Log::info("importing sql for customer id ($tenant_id)", ['process' => '[create-tenant-database]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);
//        $sql_file = BASE_DIR . '/tenant.sql';
//
//        //validate file
//        if (!is_file($sql_file)) {
//            Log::critical("tenant sql file is missing", ['process' => '[create-tenant-database]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'sql_file' => $sql_file]);
//            return false;
//        }
        if ($customer->tenant_status == 'awaiting-payment') {
            $redirect = 'payment';
        } else {
            $redirect = 'home';
        }

        $defaults = Defaults::Where('id', 1)->first();
        \Spatie\Multitenancy\Models\Tenant::forgetCurrent();

        if ($new_customer = \Spatie\Multitenancy\Models\Tenant::where('id', $tenant_id)->first()) {
            try {
                //switch to this tenants DB
                $new_customer->makeCurrent();
//                DB::connection('tenant')->unprepared(file_get_contents($sql_file));
                //import the sql file into the tenants database

                try {
                    DB::connection('tenant')->beginTransaction();
                    Artisan::call('migrate', [
                        '--database' => 'tenant',
                        '--force' => true,
                    ]);

                    Artisan::call('db:seed', [
                        '--database' => 'tenant',
                        '--force' => true,
                    ]);

                    DB::connection('tenant')->commit();
                } catch (\Exception $e) {
                    DB::connection('tenant')->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    Log::critical("Something went wrong in migration", ['process' => '[create-tenant-database]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'migration_error' => $e->getMessage()]);

                    return false;
                }

                //update general settings
//                DB::connection('tenant')
//                    ->table('settings')
//                    ->where('id', 1)
//                    ->update([
//                        'company_name' => $customer->subdomain,
//                        'company_address_line_1' => null,
//                        'company_state' => null,
//                        'company_city' => null,
//                        'company_zipcode' => null,
//                        'company_country' => null,
//                        'company_telephone' => null,
//                        'email_from_address' => $customer->tenant_email,
//                        'email_from_name' => $customer->tenant_name,
//                        'email_server_type' => 'sendmail',
//                        'saas_tenant_id' => $customer->tenant_id,
//                        'saas_status' => $customer->tenant_status,
//                        'saas_onetimelogin_key' => $auth_key,
//                        'saas_onetimelogin_destination' => $redirect,
//                        'saas_package_id' => $package->package_id,
//                        'saas_package_limits_clients' => $package->package_limits_clients,
//                        'saas_package_limits_team' => $package->package_limits_team,
//                        'saas_package_limits_projects' => $package->package_limits_projects,
//                        'saas_email_server_type' => 'local',
//                        'saas_email_forwarding_address' => $customer->tenant_email,
//                        'saas_email_local_address' => $customer->tenant_email_local_email,
//
//                        //defaults
//                        'system_language_default' => $defaults->defaults_language_default,
//                        'system_timezone' => $defaults->defaults_timezone,
//                        'system_date_format' => $defaults->defaults_date_format,
//                        'system_datepicker_format' => $defaults->defaults_datepicker_format,
//                        'system_currency_code' => $defaults->defaults_currency_code,
//                        'system_currency_symbol' => $defaults->defaults_currency_symbol,
//                        'system_currency_position' => $defaults->defaults_currency_position,
//                        'system_decimal_separator' => $defaults->defaults_decimal_separator,
//                        'system_thousand_separator' => $defaults->defaults_thousand_separator,
//                    ]);

                //update user profile
                $tmp = explode(" ", $customer->tenant_name);
                $firstname = $tmp[0];
                $lastname = trim(str_replace($firstname, '', $customer->tenant_name));
                DB::connection('tenant')
                    ->table('users')
                    ->where('id', 1)
                    ->update([
                        'first_name' => $firstname,
                        'last_name' => $lastname,
                        'email' => $customer->tenant_email,
                        'password' => $customer->tenant_password,
                        'welcome_email_sent' => 'yes',
                        'created_at' => now(),
                        'last_seen_at' => now(),
                    ]);

            } catch (Exception $e) {
                Log::critical("error importing sql file into database (" . $e->getMessage() . ")", ['process' => '[create-tenant-database]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'tenant_id' => $tenant_id]);
                return false;
            }
        }

        return true;

    }

}
