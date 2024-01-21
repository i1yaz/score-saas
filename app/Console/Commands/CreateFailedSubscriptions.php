<?php

namespace App\Console\Commands;

use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Repositories\MonthlyInvoicePackageRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateFailedSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-failed-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create failed subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $monthlyInvoiceSubscriptions = MonthlyInvoicePackage::query()
            ->select(['monthly_invoice_packages.*'])
            ->leftJoin('monthly_invoice_subscriptions', 'monthly_invoice_subscriptions.monthly_invoice_package_id', '=', 'monthly_invoice_packages.id')
            ->whereNull('monthly_invoice_subscriptions.monthly_invoice_package_id')
            ->whereDate('monthly_invoice_packages.created_at', Carbon::today())
            ->get();
        if ($monthlyInvoiceSubscriptions->isEmpty()) {
            $this->info('No failed subscriptions found');
        }
        foreach ($monthlyInvoiceSubscriptions as $monthlyInvoiceSubscription) {
            $monthlyInvoiceRepo = new MonthlyInvoicePackageRepository();
            try {
                $monthlyInvoiceSubscriptionResponse = $monthlyInvoiceRepo->createStripeSubscription($monthlyInvoiceSubscription);
                if ($monthlyInvoiceSubscriptionResponse instanceof MonthlyInvoiceSubscription) {
                    $this->info('Subscription for invoice package id '.$monthlyInvoiceSubscription->id.' created successfully');
                } else {
                    $this->error('Subscription for invoice package id '.$monthlyInvoiceSubscription->id.' failed');
                }
            } catch (\Exception $exception) {
                $this->error('Subscription for invoice package id '.$monthlyInvoiceSubscription->id.' failed');
                $this->error($exception->getMessage());
            }

        }
    }
}
