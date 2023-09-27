<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Session $session): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Session $session): bool
    {
    }

    public function delete(User $user, Session $session): bool
    {
    }

    public function restore(User $user, Session $session): bool
    {
    }

    public function forceDelete(User $user, Session $session): bool
    {
    }
}
