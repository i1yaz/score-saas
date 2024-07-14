<?php

namespace App\CronJobs\Landlord\Scheduled;
use App\Models\Landlord\Schedule;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;

class UpdateEmailDomain {

    public function __invoke() {

        if (Tenant::current()) {
            return;
        }

        //boot config settings for landlord (not needed for tenants) (delete as needed)
        runtimeLandlordCronConfig();
        //update the email domain for each tenant
        $this->updateDomain();
        Log::info('UpdateEmailDomain: Ran successfully');
    }

    /**
     * Look for scheduled tasks to update the email domain for each tenant
     *
     * @return null
     */
    private function updateDomain() {

        Log::info("Cronjob has started - (Update Email Domain)", ['process' => '[landlord-cronjob][update-email-domain]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        if ($scheduled = Schedule::on('landlord')->Where('type', 'update-email-domain')
            ->Where('status', 'new')
            ->first()) {
            $scheduled->status = 'processing';
            $scheduled->save();
            $count = 0;
            if ($tenants = \App\Models\Landlord\Tenant::on('landlord')->get()) {
                foreach ($tenants as $customer) {

                    //update the landlord database
                    $customer->email_local_email = $customer->subdomain . '@' . $scheduled->payload_1;
                    $customer->save();
                    Tenant::forgetCurrent();
                    if ($tenant = Tenant::where('id', $customer->id)->first()) {
                        try {
                            $tenant->makeCurrent();
                            Setting::on('tenant')->where('id', 1)
                                ->update([
                                    'saas_email_local_address' => $customer->email_local_email,
                                ]);
                            $count++;
                        } catch (Exception $e) {
                            Log::info("Update email domain for this tenant - ID (" . $customer->id . ") failed - error: " . $e->getMessage(), ['process' => '[landlord-cronjob][update-email-domain]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        }
                    }
                }
            }
            $scheduled->status = 'completed';
            $scheduled->save();
            Log::info("Cronjob finished - (Update Email Domain) - [$count] customers updated", ['process' => '[landlord-cronjob][update-email-domain]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return;
        }

        Log::info("Cronjob finished - (Update Email Domain) - no scheduled tasks where found", ['process' => '[landlord-cronjob][update-email-domain]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return;
    }

}
