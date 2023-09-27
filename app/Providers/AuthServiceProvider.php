<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\ParentUser;
use App\Models\Session;
use App\Models\Student;
use App\Policies\ParentUserPolicy;
use App\Policies\SessionPolicy;
use App\Policies\StudentPolicy;
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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
