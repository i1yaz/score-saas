<?php

namespace App\Repositories;

use App\Models\Tutor;

class TutorRepository extends BaseRepository
{
    protected array $fieldSearchable = [
        'first_name',
        'last_name',
        'email',
        'secondary_email',
        'phone',
        'secondary_phone',
        'picture',
        'resume',
        'start_date',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Tutor::class;
    }

    public function find(int $id, array $columns = ['*'])
    {
        $tutor = Tutor::query()
            ->select(
                [
                    'tutors.id',
                    'tutors.email',
                    'tutors.first_name',
                    'tutors.last_name',
                    'tutors.status',
                    'tutors.phone',
                    'tutors.start_date',
                ])
            ->selectRaw('CASE WHEN s1.id IS NOT NULL THEN s1.id ELSE s2.id END as student_id')
            ->selectRaw('CASE WHEN p1.id IS NOT NULL THEN p1.id ELSE p2.id END as parent_id')
            ->leftJoin('student_tutoring_package_tutor', 'student_tutoring_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', 'student_tutoring_package_tutor.student_tutoring_package_id')
            ->leftJoin('monthly_invoice_package_tutor', 'monthly_invoice_package_tutor.tutor_id', 'tutors.id')
            ->leftJoin('monthly_invoice_packages', 'monthly_invoice_packages.id', 'monthly_invoice_package_tutor.monthly_invoice_package_id')
            ->leftJoin('students as s1', 's1.id', 'student_tutoring_packages.student_id')
            ->leftJoin('students as s2', 's2.id', 'monthly_invoice_packages.student_id')
            ->leftJoin('parents as p1', 'p1.id', 's1.parent_id')
            ->leftJoin('parents as p2', 'p2.id', 's2.parent_id')
            ->where('tutors.id', $id)
            ->groupBy('tutors.id')
            ->first();

        return $tutor;
    }
}
