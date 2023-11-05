<?php

namespace App\Policies;

use App\Models\ParentUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class ParentUserPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        if (Auth::user()->hasRole(['student', 'parent'])) {
            return true;
        }

        return false;

    }

    public function view(User $user, ParentUser $parent): bool
    {

        if (Auth::user()->hasRole(['student'])) {
            return $user->parent_id == $parent->id;
        }
        if (Auth::user()->hasRole(['parent'])) {
            return $user->id == $parent->id;
        }
        if (Auth::user()->hasRole(['tutor'])) {
            return $user->id == $parent->tutor_id;
        }

        return false;
    }

    public function update(User $user, ParentUser $parent): bool
    {
        if (Auth::user()->hasRole(['student'])) {
            return $user->parent_id == $parent->id;
        }
        if (Auth::user()->hasRole(['parent'])) {
            return $user->id == $parent->id;
        }
        return false;
    }
}
