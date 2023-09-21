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
        'internal_noted',
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
}
