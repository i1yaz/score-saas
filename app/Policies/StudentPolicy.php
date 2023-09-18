<?php

namespace App\Policies;

use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class StudentPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (\Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        if (\Auth::user()->hasRole(['student', 'parent'])) {
            return true;
        }

        return false;

    }

    public function view(User $user, Student $student): bool
    {
        if (\Auth::user()->hasRole(['student', 'parent'])) {
            return $user->id == $student->id || $user->id == $student->parent_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Student $student): bool
    {
    }

    public function delete(User $user, Student $student): bool
    {
    }

    public function restore(User $user, Student $student): bool
    {
    }

    public function forceDelete(User $user, Student $student): bool
    {
    }
}
