<?php

namespace App\Console\Commands;

use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\Session;
use Illuminate\Console\Command;

class ReportMonthlyPackageUsageCommand extends Command
{
    protected $signature = 'report:monthly-package-usage';

    protected $description = 'Command description';

    public function handle(): void
    {
        setStripeApiKey();
        $monthlyPackages = MonthlyInvoicePackage::select([
            'monthly_invoice_packages.id','monthly_invoice_subscriptions.subscription_id',
            'monthly_invoice_subscriptions.stripe_item_id','monthly_invoice_subscriptions.stripe_price_id',
            'monthly_invoice_subscriptions.stripe_minutes_price_id','monthly_invoice_subscriptions.stripe_minutes_item_id'
        ])
            ->with(['sessions'])
            ->join('monthly_invoice_subscriptions', function ($join){
                $join->on('monthly_invoice_subscriptions.monthly_invoice_package_id', '=', 'monthly_invoice_packages.id')
                    ->where(function ($q){
                        $q->where('monthly_invoice_subscriptions.is_active', MonthlyInvoiceSubscription::ACTIVE)
                            ->where(function ($q){
                                $q->whereNotNull('monthly_invoice_subscriptions.subscription_id')
                                    ->where('monthly_invoice_subscriptions.subscription_id','!=','');
                            });
                    });
            })
            ->whereHas('sessions', function ($q){
                $q->select(['id','monthly_invoice_package_id','scheduled_date','start_time','end_time','session_completion_code','attended_duration',
                    'charged_missed_session','attended_start_time','attended_end_time','charge_missed_time','is_billed'])->where('sessions.is_billed', Session::UN_BILLED);
            })->get();

        foreach ($monthlyPackages as $monthlyPackage ){
            $totalTimeInSeconds = 0;
            foreach ($monthlyPackage->sessions as $session){
                $totalTimeInSeconds += getTotalChargedTimeOfSessionFromSessionInSeconds($session);
            }
            //get total hours and minutes from seconds
            $totalHours = floor($totalTimeInSeconds / 3600);
            $totalMinutes = floor(($totalTimeInSeconds / 60) % 60);
            if (!empty($monthlyPackage->subscription_id) && !empty($monthlyPackage->stripe_item_id) && !empty($monthlyPackage->stripe_minutes_item_id)){
                createUsageRecord($monthlyPackage->stripe_item_id,$totalHours,'set');
                createUsageRecord($monthlyPackage->stripe_minutes_item_id,$totalMinutes,'set');
            }
        }
        $this->info('Report generated successfully');

    }
}
