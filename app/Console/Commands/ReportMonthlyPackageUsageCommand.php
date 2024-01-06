<?php

namespace App\Console\Commands;

use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReportMonthlyPackageUsageCommand extends Command
{
    protected $signature = 'report:monthly-package-usage';

    protected $description = 'Report sessions to stripe billing API.';

    public function handle(): void
    {
        $now = Carbon::now();
        if ($now->day==1) {
            setStripeApiKey();
            $monthlyPackages = MonthlyInvoicePackage::select([
                'monthly_invoice_packages.id', 'monthly_invoice_subscriptions.subscription_id',
                'monthly_invoice_subscriptions.stripe_item_id', 'monthly_invoice_subscriptions.stripe_price_id',
                'monthly_invoice_subscriptions.stripe_minutes_price_id', 'monthly_invoice_subscriptions.stripe_minutes_item_id'
            ])
                ->with(['LastMonthUnbilledSessions'])
                ->join('monthly_invoice_subscriptions', function ($join) {
                    $join->on('monthly_invoice_subscriptions.monthly_invoice_package_id', '=', 'monthly_invoice_packages.id')
                        ->where(function ($q) {
                            $q->where('monthly_invoice_subscriptions.is_active', MonthlyInvoiceSubscription::ACTIVE)
                                ->where(function ($q) {
                                    $q->whereNotNull('monthly_invoice_subscriptions.subscription_id')
                                        ->where('monthly_invoice_subscriptions.subscription_id', '!=', '');
                                });
                        });
                })
                ->get();

            foreach ($monthlyPackages as $monthlyPackage) {
                $billedSessions = [];
                $totalTimeInSeconds = 0;
                foreach ($monthlyPackage->LastMonthUnbilledSessions as $session) {
                    $billedSessions[] = $session->id;
                    $totalTimeInSeconds += getTotalChargedTimeOfSessionFromSessionInSeconds($session);
                }
                $totalHours = floor($totalTimeInSeconds / 3600);
                $totalMinutes = floor(($totalTimeInSeconds / 60) % 60);
                if (!empty($monthlyPackage->subscription_id) && !empty($monthlyPackage->stripe_item_id) && !empty($monthlyPackage->stripe_minutes_item_id)) {
                    createUsageRecord($monthlyPackage->stripe_item_id, $totalHours, 'set');
                    createUsageRecord($monthlyPackage->stripe_minutes_item_id, $totalMinutes, 'set');
                    Session::whereIn('id', $billedSessions)->update(['is_billed' => true]);
                }
            }
            $this->info('Report generated successfully at '. $now->toDateTimeString() .'-'.Carbon::now()->toDateTimeString());
        }
        $this->info('Nothing to report at '. $now->toDateTimeString());

    }
}
