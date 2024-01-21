<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\Tutor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class SessionPolicy
{
    use HandlesAuthorization;
    //Before method is inside the AuthServiceProvider

    public function viewAny(User $user): bool
    {
        return true;

    }

    public function view(User $user, Session $session): bool
    {

        if ($user->hasRole(['tutor']) && $user instanceof Tutor) {
            return $user->id === $session->tutor_id;
        }

        return false;

    }

    public function create(User $user): bool
    {
        return true;

    }

    public function update(User $user, Session $session): bool
    {
        if ($user->hasRole(['tutor']) && $user instanceof Tutor) {
            return $user->id === $session->tutor_id;
        }

        return false;

    }

    public function delete(User $user, Session $session): bool
    {
        if ($user->hasRole(['tutor']) && $user instanceof Tutor) {
            return $user->id === $session->tutor_id;
        }

        return false;

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
