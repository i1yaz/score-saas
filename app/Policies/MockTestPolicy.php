<?php

namespace App\Policies;

use App\Models\MockTest;
use App\Models\Proctor;
use App\Models\Tutor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class MockTestPolicy
{
    use HandlesAuthorization;
    public function before(Authenticatable $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        if (Auth::user()->hasRole(['client' ])) {
            abort(403, getRoleOfLoggedInUser().'s do not have access to Invoices');
        }
    }
    public function viewAny(Authenticatable $user): bool
    {

    }

    public function view(Authenticatable $user, MockTest $mockTest): bool
    {
        if (Auth::user()->hasRole(['proctor']) && $mockTest->proctorable_type === Proctor::class) {
            return $mockTest->proctorable_id == Auth::user()->id;
        }
        if (Auth::user()->hasRole(['tutor']) && $mockTest->proctorable_type === Tutor::class) {
            return $mockTest->proctorable_id == Auth::user()->id;
        }
        return false;
    }

    public function create(Authenticatable $user): bool
    {
    }

    public function update(Authenticatable $user, MockTest $mockTest): bool
    {
    }

    public function delete(Authenticatable $user, MockTest $mockTest): bool
    {
    }

    public function restore(Authenticatable $user, MockTest $mockTest): bool
    {
    }

    public function forceDelete(Authenticatable $user, MockTest $mockTest): bool
    {
    }
}
