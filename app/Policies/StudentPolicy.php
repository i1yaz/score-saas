<?php

namespace App\Policies;

use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;


class StudentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
//        ['super-admin','admin','student','parent','tutor','proctor','client','developer']
        if (\Auth::user()->hasRole(['super-admin','admin','student','parent'])){
            return true;
        }
        return false;
    }

    public function view(User $user, Student $model): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Student $model): bool
    {
    }

    public function delete(User $user, Student $model): bool
    {
    }

    public function restore(User $user, Student $model): bool
    {
    }

    public function forceDelete(User $user, Student $model): bool
    {
    }
}
