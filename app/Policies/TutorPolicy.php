<?php

namespace App\Policies;

use App\Models\Tutor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class TutorPolicy
{
    use HandlesAuthorization;

    public function before(Authenticatable $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        if (Auth::user()->hasRole(['client', 'proctor', 'developer'])) {
            abort(403, getRoleOfLoggedInUser().'s do not have access to Invoices');
        }
    }

    //    public function viewAny(Authenticatable $user): bool
    //    {
    //
    //    }

    public function view(Authenticatable $user, Tutor $tutor): bool
    {
        if (Auth::user()->hasRole(['tutor'])) {
            return $tutor->id == Auth::user()->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        abort(403, getRoleOfLoggedInUser().'s do not have access to create tutor');
    }

    public function update(Authenticatable $user, Tutor $tutor): bool
    {
        if (Auth::user()->hasRole(['tutor'])) {
            return $tutor->id == Auth::user()->id;
        }

        return false;
    }

    //    public function delete(Authenticatable $user, Tutor $tutor): bool
    //    {
    //    }
    //
    //    public function restore(Authenticatable $user, Tutor $tutor): bool
    //    {
    //    }
    //
    //    public function forceDelete(Authenticatable $user, Tutor $tutor): bool
    //    {
    //    }
}
