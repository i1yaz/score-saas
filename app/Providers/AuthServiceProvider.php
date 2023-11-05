<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\ParentUser;
use App\Models\Payment;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Policies\ClientPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\MonthlyInvoicePackagePolicy;
use App\Policies\ParentUserPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\SessionPolicy;
use App\Policies\StudentPolicy;
use App\Policies\studentTutoringPackagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        ParentUser::class => ParentUserPolicy::class,
        Student::class => StudentPolicy::class,
        Session::class => SessionPolicy::class,
        Client::class => ClientPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        StudentTutoringPackage::class => studentTutoringPackagePolicy::class,
        MonthlyInvoicePackage::class => MonthlyInvoicePackagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
