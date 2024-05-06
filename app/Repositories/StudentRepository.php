<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'school_id',
        'first_name',
        'last_name',
        'email_known',
        'testing_accommodation',
        'testing_accommodation_nature',
        'official_baseline_act_score',
        'official_baseline_sat_score',
        'test_anxiety_challenge',
        'parent_id',
        'added_by',
        'status',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Student::class;
    }

    public function getStudents($email,$limit = 5)
    {
        $email = trim($email);
        return Student::active()
            ->select(['students.id as id', 'students.email as text'])
            ->where('students.email', 'LIKE', "%{$email}%")
            ->limit($limit)
            ->get();
    }
}
