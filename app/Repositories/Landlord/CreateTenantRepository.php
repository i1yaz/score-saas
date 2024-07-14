<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\Defaults;
use App\Models\Landlord\Package;
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

    public function createTenant(Tenant $tenant, Package $package , $auth_key = '') {

        //create database
        if ($database_name = $this->databaseRepository->createDatabase()) {
            Tenant::where('id', $tenant->id)
                ->update([
                    'database' => $database_name,
                ]);
        } else {
            return false;
        }

        //populate the database
        if (!$this->configureDB($tenant, $package, $auth_key)) {
            return false;
        }

        //return new database information
        return true;

    }
    public function configureDB($tenant = [], $package = [], $auth_key = '') {
        $tenant_id = $tenant->id;
        Log::info("importing sql for customer id ($tenant_id)", ['process' => '[create-tenant-database]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => 1]);
//        $sql_file = BASE_DIR . '/tenant.sql';
//
//        //validate file
//        if (!is_file($sql_file)) {
//            Log::critical("tenant sql file is missing", ['process' => '[create-tenant-database]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'sql_file' => $sql_file]);
//            return false;
//        }
        if ($tenant->status == 'awaiting-payment') {
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
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--force' => true,
                ]);

                Artisan::call('db:seed', [
                    '--database' => 'tenant',
                    '--force' => true,
                ]);

                DB::connection('tenant')
                    ->table('settings')
                    ->where('id', 1)
                    ->updateOrInsert([
                        'company_name' => $tenant->subdomain,
                        'company_address_line_1' => null,
                        'company_state' => null,
                        'company_city' => null,
                        'company_zipcode' => null,
                        'company_country' => null,
                        'company_telephone' => null,
                        'email_from_address' => $tenant->email,
                        'email_from_name' => $tenant->name,
                        'email_server_type' => 'sendmail',
                        'saas_tenant_id' => $tenant->id,
                        'saas_status' => $tenant->status,
                        'saas_onetime_login_key' => $auth_key,
                        'saas_onetime_login_destination' => $redirect,
                        'saas_package_id' => $package->package_id,
                        'saas_package_limits_tutors' => $package->package_limits_clients,
                        'saas_package_limits_students' => $package->max_students,
                        'saas_package_limits_monthly_packages' => $package->max_monthly_packages,
                        'saas_package_limits_student_packages' => $package->max_student_packages,
                        'saas_email_server_type' => 'local',
                        'saas_email_forwarding_address' => $tenant->email,
                        'saas_email_local_address' => $tenant->email_local_email,
                        'email_verified_at' => null,
                        //defaults
                        'system_language_default' => $defaults->language_default??'english',
                        'system_timezone' => $defaults->timezone,
                        'system_date_format' => $defaults->date_format,
                        'system_datepicker_format' => $defaults->datepicker_format,
                        'system_currency_code' => $defaults->currency_code,
                        'system_currency_symbol' => $defaults->currency_symbol,
                        'system_currency_position' => $defaults->currency_position,
                        'system_decimal_separator' => $defaults->decimal_separator,
                        'system_thousand_separator' => $defaults->thousand_separator,
                    ]);

                //update user profile
                $tmp = explode(" ", $tenant->name);
                $firstname = $tmp[0];
                $lastname = trim(str_replace($firstname, '', $tenant->name));
                DB::connection('tenant')
                    ->table('users')
                    ->where('id', 1)
                    ->update([
                        'first_name' => $firstname,
                        'last_name' => $lastname,
                        'email' => $tenant->email,
                        'password' => $tenant->password,
                        'is_welcome_email_sent' => true,
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
