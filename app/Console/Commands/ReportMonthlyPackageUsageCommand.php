<?php

namespace App\Console\Commands;

use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\Session;
use Illuminate\Console\Command;

class ReportMonthlyPackageUsageCommand extends Command
{
    protected $signature = 'report:monthly-package-usuage';

    protected $description = 'Command description';

    public function handle(): void
    {
        $monthlyPackage = MonthlyInvoicePackage::select(['monthly_invoice_packages.id'])
            ->with(['sessions'])
            ->join('monthly_invoice_subscriptions', function ($join){
                $join->on('monthly_invoice_subscriptions.monthly_invoice_package_id', '=', 'monthly_invoice_packages.id')
                    ->where('monthly_invoice_subscriptions.is_active', MonthlyInvoiceSubscription::ACTIVE);
            })
            ->whereHas('sessions', function ($q){
                $q->select(['id','monthly_invoice_package_id','scheduled_date','start_time','end_time','session_completion_code','attended_duration',
                    'charged_missed_session','attended_start_time','attended_end_time','charge_missed_time','is_billed'])->where('sessions.is_billed', Session::UN_BILLED);
            })->get();

    }
}
