<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\MockTest;
use App\Models\MonthlyInvoicePackage;
use App\Models\ParentUser;
use App\Models\Payment;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Models\Tutor;
use App\Policies\ClientPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\MockTestPolicy;
use App\Policies\MonthlyInvoicePackagePolicy;
use App\Policies\ParentUserPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\SessionPolicy;
use App\Policies\StudentPolicy;
use App\Policies\studentTutoringPackagePolicy;
use App\Policies\TutorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Tutor::class => TutorPolicy::class,
        MockTest::class => MockTestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {

            if (\Auth::user()->hasRole(['super-admin', 'admin'])) {
                return true;
            }
        });
    }
}
