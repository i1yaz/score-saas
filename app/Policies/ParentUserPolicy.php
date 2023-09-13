<?php

namespace App\Policies;

use App\Models\ParentUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParentUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, ParentUser $parentUser): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, ParentUser $parentUser): bool
    {
    }

    public function delete(User $user, ParentUser $parentUser): bool
    {
    }

    public function restore(User $user, ParentUser $parentUser): bool
    {
    }

    public function forceDelete(User $user, ParentUser $parentUser): bool
    {
    }
}
