<?php

namespace App\DataTables;

use App\Models\ParentUser;
use App\Models\Student;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TutorDataTable implements IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {

        $order = $columns[$order] ?? $order;
        $tutors = Tutor::query()
            ->select(
                [
                    'tutors.id',
                    'tutors.email',
                    'tutors.first_name',
                    'tutors.last_name',
                    'tutors.status',
                    'tutors.phone',
                    'tutors.start_date',
                    'student_tutoring_packages.student_id as stp_student_id',
                    'monthly_invoice_packages.student_id as mip_student_id',
                ])
            ->leftJoin('student_tutoring_package_tutor', 'student_tutoring_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', 'student_tutoring_package_tutor.student_tutoring_package_id')
            ->leftJoin('monthly_invoice_package_tutor', 'monthly_invoice_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('monthly_invoice_packages', 'monthly_invoice_packages.id', 'monthly_invoice_package_tutor.monthly_invoice_package_id');

        $tutors = static::getModelQueryBySearch($search, $tutors);
        $tutors = $tutors->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);
        return $tutors->groupBy('tutors.id')->get();
    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $tutors = Tutor::query()
            ->selectRaw('count(distinct tutors.id) as total')
            ->leftJoin('student_tutoring_package_tutor', 'student_tutoring_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', 'student_tutoring_package_tutor.student_tutoring_package_id')
            ->leftJoin('monthly_invoice_package_tutor', 'monthly_invoice_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('monthly_invoice_packages', 'monthly_invoice_packages.id', 'monthly_invoice_package_tutor.monthly_invoice_package_id');
        $tutors = static::getModelQueryBySearch($search, $tutors);
        $tutors = $tutors->first();
        return $tutors->total??0;
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $tutor) {
                $nestedData['email'] = $tutor->email;
                $nestedData['first_name'] = $tutor->first_name;
                $nestedData['last_name'] = $tutor->last_name;
                $nestedData['phone'] = $tutor->phone;
                $nestedData['start_date'] = Carbon::parse($tutor->start_date)->toDateString();
                $nestedData['status'] = view('partials.status_badge', ['status' => $tutor->status, 'text_success' => 'Active', 'text_danger' => 'Inactive'])->render();
                $nestedData['action'] = view('tutors.actions', ['tutor' => $tutor])->render();
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
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
            $records = $records->where('tutors.id', Auth::id());
        }
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $records = $records->where(function ($q) {
                $students = Student::select(['id'])->where('parent_id', Auth::id())->get();
                if ($students->isEmpty()){
                    $students = [];
                }else{
                    $students = $students->pluck('id')->toArray();
                }
                $q->whereIn('student_tutoring_packages.student_id', $students)
                    ->orWhereIn('monthly_invoice_packages.student_id', $students);
            });
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $records = $records->where(function ($q) {
                $q->where('student_tutoring_packages.student_id', Auth::id())
                    ->orWhere('monthly_invoice_packages.student_id', Auth::id());
            });
        }

        return $records;
    }

    public static function totalRecords(): int
    {
        $tutors = Tutor::query()
            ->selectRaw('count(distinct tutors.id) as total')
            ->leftJoin('student_tutoring_package_tutor', 'student_tutoring_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', 'student_tutoring_package_tutor.student_tutoring_package_id')
            ->leftJoin('monthly_invoice_package_tutor', 'monthly_invoice_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('monthly_invoice_packages', 'monthly_invoice_packages.id', 'monthly_invoice_package_tutor.monthly_invoice_package_id');
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof ParentUser) {
            $tutors = $tutors->where('id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $tutors = $tutors->where(function ($q) {
                $q->where('student_tutoring_packages.student_id', Auth::id())
                    ->orWhere('monthly_invoice_packages.student_id', Auth::id());
            });
        }
        $tutors = $tutors->first();
        return $tutors->total??0;
    }
}
