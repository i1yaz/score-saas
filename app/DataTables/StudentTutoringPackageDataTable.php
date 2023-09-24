<?php

namespace App\DataTables;

use App\Models\StudentTutoringPackage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StudentTutoringPackageDataTable implements IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $columns = [
            'package_id' => 'id',
        ];
        $order = $columns[$order] ?? $order;
        $studentTutoringPackages = StudentTutoringPackage::query()
            ->select(['student_tutoring_packages.id', 'students.email as student', 'tutoring_package_types.name as package', 'tutoring_locations.name as location',
                'student_tutoring_packages.notes as notes', 'student_tutoring_packages.hours as hours',
                'student_tutoring_packages.start_date as start_date'])
            ->join('students', 'student_tutoring_packages.student_id', 'students.id')
            ->join('tutoring_package_types', 'student_tutoring_packages.tutoring_package_type_id', 'tutoring_package_types.id')
            ->join('tutoring_locations', 'student_tutoring_packages.tutoring_location_id', 'tutoring_locations.id');
        $studentTutoringPackages = static::getModelQueryBySearch($search, $studentTutoringPackages);
        $studentTutoringPackages = $studentTutoringPackages->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        return $studentTutoringPackages->get();

    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $studentTutoringPackages = StudentTutoringPackage::query()->select(['id']);
        $studentTutoringPackages = static::getModelQueryBySearch($search, $studentTutoringPackages);

        return $studentTutoringPackages->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $studentTutoringPackage) {
                $nestedData['package_id'] = getPackageIdFromId($studentTutoringPackage->id);
                $nestedData['student'] = $studentTutoringPackage->student;
                $nestedData['tutoring_package_type'] = $studentTutoringPackage->package;
                $nestedData['notes'] = $studentTutoringPackage->notes;
                $nestedData['hours'] = $studentTutoringPackage->hours;
                $nestedData['location'] = $studentTutoringPackage->location;
                $nestedData['start_date'] = Carbon::parse($studentTutoringPackage->start_date)->format('j F,Y');
                $nestedData['action'] = view('student_tutoring_packages.actions', ['studentTutoringPackage' => $studentTutoringPackage])->render();
                $data[] = $nestedData;

            }
        }

        return $data;

    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        if (! empty($search)) {
            $records = $records->where(function ($q) use ($search) {
                $q->where('student_tutoring_packages.id', 'like', "%{$search}%")
                    ->orWhere('student_tutoring_packages.hours', 'like', "%{$search}%")
                    ->orWhere('students.email', 'like', "%{$search}%")
                    ->orWhere('student_tutoring_packages.start_date', 'like', "%{$search}%");
            });
        }

        return $records;
    }

    public static function totalRecords(): int
    {
        $students = StudentTutoringPackage::query()->select(['id']);

        return $students->count();
    }
}
