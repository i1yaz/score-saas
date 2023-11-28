<?php

namespace App\Policies;

use App\Models\StudentTutoringPackage;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class studentTutoringPackagePolicy
{
    use HandlesAuthorization;

    public function before(Authenticatable $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        if (Auth::user()->hasRole([ 'tutor','proctor','client','developer'])) {
            abort(403, getRoleOfLoggedInUser() . 's do not have access to Student Tutoring Packages');
        }
    }
    public function viewAny(Authenticatable $user): bool
    {

    }

    public function view(Authenticatable $user, StudentTutoringPackage $studentTutoringPackage): bool
    {
        if ($studentTutoringPackage->student_id == Auth::user()->id) {
            return true;
        }
        return false;
    }

    public function create(Authenticatable $user): bool
    {
    }

    public function update(Authenticatable $user, StudentTutoringPackage $studentTutoringPackage): bool
    {
        if ($studentTutoringPackage->student_id == Auth::user()->id) {
            return true;
        }
        return false;
    }

    public function delete(Authenticatable $user, StudentTutoringPackage $studentTutoringPackage): bool
    {
        if ($studentTutoringPackage->student_id == Auth::user()->id) {
            return true;
        }
        return false;
    }

    public function restore(Authenticatable $user, StudentTutoringPackage $studentTutoringPackage): bool
    {
    }

    public function forceDelete(Authenticatable $user, StudentTutoringPackage $studentTutoringPackage): bool
    {
    }
}
