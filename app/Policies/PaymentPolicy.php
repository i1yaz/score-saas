<?php

namespace App\Policies;

use App\Models\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class PaymentPolicy
{
    use HandlesAuthorization;

    //Before method is inside the AuthServiceProvider
    public function viewAny(Authenticatable $user): bool
    {
        if (! (Auth::user()->hasRole(['super-admin', 'admin', 'client', 'student', 'parent']))) {
            return false;
        }
    }

    public function view(Authenticatable $user, Payment $payment): bool
    {
        if (! (Auth::user()->hasRole(['super-admin', 'admin', 'client', 'student', 'parent']))) {
            return false;
        }
    }

    public function create(Authenticatable $user): bool
    {
        if (! (Auth::user()->hasRole(['super-admin', 'admin', 'client', 'student', 'parent']))) {
            return false;
        }
    }

    public function update(Authenticatable $user, Payment $payment): bool
    {
        if (! (Auth::user()->hasRole(['super-admin', 'admin', 'client', 'student', 'parent']))) {
            return false;
        }
    }

    public function delete(Authenticatable $user, Payment $payment): bool
    {
        if (! (Auth::user()->hasRole(['super-admin', 'admin', 'client', 'student', 'parent']))) {
            return false;
        }
    }

    public function restore(Authenticatable $user, Payment $payment): bool
    {
        if (! (Auth::user()->hasRole(['super-admin', 'admin', 'client', 'student', 'parent']))) {
            return false;
        }
    }

    public function forceDelete(Authenticatable $user, Payment $payment): bool
    {
        if (! (Auth::user()->hasRole(['super-admin', 'admin', 'client', 'student', 'parent']))) {
            return false;
        }
    }
}
