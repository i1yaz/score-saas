<?php

namespace App\Console\Commands;

use App\Mail\SessionSubmittedMail;
use App\Models\MailTemplate;
use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ReportMonthlyPackageUsageCommand extends Command
{
    protected $signature = 'report:monthly-package-usage';

    protected $description = 'Report sessions to stripe billing API.';

    public function handle(): void
    {
        $now = Carbon::now();
        if ($now->day == 1 || \App::environment(['local'])) {
            setStripeApiKey();
            $monthlyPackages = MonthlyInvoicePackage::select([
                'monthly_invoice_packages.id', 'monthly_invoice_subscriptions.subscription_id',
                'monthly_invoice_subscriptions.stripe_item_id', 'monthly_invoice_subscriptions.stripe_price_id',
                'monthly_invoice_subscriptions.stripe_minutes_price_id', 'monthly_invoice_subscriptions.stripe_minutes_item_id',
                'students.email as student_email', 'students.first_name as student_first_name', 'students.last_name as student_last_name',
                'monthly_invoice_packages.hourly_rate', 'monthly_invoice_packages.start_date',
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
                ->join('students', 'monthly_invoice_packages.student_id', 'students.id')
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
                if (! empty($monthlyPackage->subscription_id)
                    && ! empty($monthlyPackage->stripe_item_id)
                    && ! empty($monthlyPackage->stripe_minutes_item_id)
                    && ! ($totalHours+$totalMinutes ) == 0
                ) {
                    createUsageRecord($monthlyPackage->stripe_item_id, $totalHours, 'set');
                    createUsageRecord($monthlyPackage->stripe_minutes_item_id, $totalMinutes, 'set');
                    Session::whereIn('id', $billedSessions)->update(['is_billed' => true]);
                    $hoursAmount = (float) formatAmountWithoutCurrency($monthlyPackage->hourly_rate * $totalHours);
                    $minutesAmount = (float) (formatAmountWithoutCurrency($monthlyPackage->hourly_rate / 60)) * $totalMinutes;
                    $input = [
                        'time' => "{$totalHours}:{$totalMinutes}",
                        'student_email' => $monthlyPackage->student_email,
                        'student_first_name' => $monthlyPackage->student_first_name,
                        'student_last_name' => $monthlyPackage->student_last_name,
                        'bill_amount' => $hoursAmount + $minutesAmount,
                        'start_time' => $monthlyPackage->start_date,
                    ];
                    $template = MailTemplate::where('mailable', SessionSubmittedMail::class)->firstOrFail();
                    if ($template->status) {
                        Mail::to($monthlyPackage->student_email)->send(new SessionSubmittedMail($input));
                    }
                    $this->info('Report generated successfully at '.$now->toDateTimeString().'-'.Carbon::now()->toDateTimeString().' for package '.$monthlyPackage->id ?? 'nothing');
                }

            }

        } else {
            $this->info('Nothing to report at '.$now->toDateTimeString());

        }
        $this->info('Command executed at '.$now->toDateTimeString());
    }
}
