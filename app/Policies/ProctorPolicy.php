<?php

namespace App\Policies;

use App\Models\Proctor;
use App\Models\Tutor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class ProctorPolicy
{
    use HandlesAuthorization;
    public function before(Authenticatable $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        if (Auth::user()->hasRole(['client'])) {
            abort(403, getRoleOfLoggedInUser().'s do not have access to Invoices');
        }
    }

    public function view(Authenticatable $user, Proctor $proctor): bool
    {
        if (Auth::user()->hasRole(['proctor'])) {
            return $proctor->id == Auth::user()->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        abort(403, getRoleOfLoggedInUser().'s do not have access to create proctor');
    }

    public function update(Authenticatable $user, Proctor $proctor): bool
    {
        if (Auth::user()->hasRole(['proctor'])) {
            return $proctor->id == Auth::user()->id;
        }
        return false;
    }

}
