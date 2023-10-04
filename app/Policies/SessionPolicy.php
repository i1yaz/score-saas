<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class SessionPolicy
{
    use HandlesAuthorization;

    public function before(\Illuminate\Foundation\Auth\User $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return true;

    }

    public function view(User $user, Session $session): bool
    {
        return true;

    }

    public function create(User $user): bool
    {
        return true;

    }

    public function update(User $user, Session $session): bool
    {
        return true;

    }

    public function delete(User $user, Session $session): bool
    {
        return true;

    }

    public function restore(User $user, Session $session): bool
    {
        return true;

    }

    public function forceDelete(User $user, Session $session): bool
    {
        return true;

    }
}
