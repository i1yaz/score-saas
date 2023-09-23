<?php

namespace App\Repositories;

use App\Models\StudentTutoringPackage;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentTutoringPackageRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'student_id',
        'package_type_id',
        'tutor_id',
        'notes',
        'internal_notes',
        'hours',
        'hourly_rate',
        'tutoring_location_id',
        'discount',
        'discount_type',
        'start_date',
        'tutor_hourly_rate'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return StudentTutoringPackage::class;
    }

    public function create(array $input): StudentTutoringPackage
    {
        $input['auth_guard'] = Auth::guard()->name;
        $input['added_by'] = Auth::id();

        $model = $this->model->newInstance($input);
        $model->save();

        return $model;
    }

    public function show($id): StudentTutoringPackage
    {
        return StudentTutoringPackage::query()
            ->with(['tutors','subjects'])
            ->select([
                'student_tutoring_packages.*',
                'students.email as student_email',
                'package_types.name as package_name',
                'package_types.hours as package_hours',
                'tutoring_locations.name as location_name'
            ])
            ->join('students','student_tutoring_packages.student_id','students.id')
            ->join('package_types','student_tutoring_packages.package_type_id','package_types.id')
            ->join('tutoring_locations','student_tutoring_packages.tutoring_location_id','tutoring_locations.id')
            ->where('student_tutoring_packages.id',$id)
            ->first();
    }
}
