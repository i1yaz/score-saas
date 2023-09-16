<?php

namespace App\Policies;

use App\Models\ParentUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class ParentUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if (\Auth::user()->hasRole(['super-admin','admin','student','parent','tutor','proctor','client','developer'])){
            return true;
        }
        return false;

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
