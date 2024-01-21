<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class StudentPolicy
{
    use HandlesAuthorization;

    //Before method is inside the AuthServiceProvider
    public function viewAny(User $user): bool
    {
        if (Auth::user()->hasRole(['student', 'parent', 'tutor'])) {
            return true;
        }

        return false;

    }

    public function view(User $user, Student $student): bool
    {
        if (Auth::user()->hasRole(['tutor'])) {
            if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
                $student = $student->whereHas('tutoringPackages.tutors', function ($q) use ($student) {
                    $q->where('tutor_id', Auth::id())->where('students.id', $student->student_id);
                });
            }
            if ($student->first()) {
                return true;
            }
        }
        if (Auth::user()->hasRole(['student'])) {
            return $user->id == $student->student_id;
        }
        if (Auth::user()->hasRole(['parent'])) {
            return $user->id == $student->parent_id;
        }

        return false;
    }

    public function update(User $user, Student $student): bool
    {
        if (Auth::user()->hasRole(['tutor'])) {
            if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
                $student = $student->whereHas('tutoringPackages.tutors', function ($q) use ($student) {
                    $q->where('tutor_id', Auth::id())->where('students.id', $student->student_id);
                });
            }
            if ($student->first()) {
                return true;
            }
        }
        if (Auth::user()->hasRole(['student'])) {
            return $user->id == $student->id;
        }
        if (Auth::user()->hasRole(['parent'])) {
            return $user->id == $student->parent_id;
        }

        return false;
    }
}
