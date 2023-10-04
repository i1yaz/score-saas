<?php

namespace App\DataTables;

use App\Models\ParentUser;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class StudentsDataTable implements IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $columns = [
            'family_code' => 'parent_id',
        ];
        $order = $columns[$order] ?? $order;
        $students = Student::query()->select(['id', 'parent_id', 'email', 'first_name', 'last_name', 'official_baseline_act_score', 'official_baseline_sat_score', 'status']);
        $students = static::filterStudentsOfTutor($students);
        $students = static::getModelQueryBySearch($search, $students);
        $students = $students->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        return $students->get();

    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $students = Student::query()->select(['id', 'parent_id']);
        $students = static::filterStudentsOfTutor($students);
        $students = static::getModelQueryBySearch($search, $students);

        return $students->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $student) {
                $nestedData['family_code'] = getFamilyCodeFromId($student->parent_id);
                $nestedData['email'] = $student->email;
                $nestedData['first_name'] = $student->first_name;
                $nestedData['last_name'] = $student->last_name;
                $nestedData['status'] = view('partials.status_badge', ['status' => $student->status, 'text_success' => 'Active', 'text_danger' => 'Inactive'])->render();
                $nestedData['action'] = view('students.actions', ['student' => $student])->render();
                $data[] = $nestedData;
            }
        }

        return $data;

    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        if (! empty($search)) {
            $records = $records->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $records = $records->where('parent_id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $records = $records->where('id', Auth::id());
        }

        return $records;
    }

    public static function totalRecords(): int
    {
        $students = Student::query()->select(['id']);
        $students = static::filterStudentsOfTutor($students);
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $students = $students->where('parent_id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $students = $students->where('id', Auth::id());
        }

        return $students->count();
    }

    public static function filterStudentsOfTutor($students)
    {
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
            $students = $students->whereHas('tutoringPackages.tutors', function ($q) {
                $q->where('tutor_id', Auth::id());
            });
        }

        return $students;
    }
}
