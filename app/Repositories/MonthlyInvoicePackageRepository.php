<?php

namespace App\Repositories;

use App\Models\MonthlyInvoicePackage;
use App\Repositories\BaseRepository;

class MonthlyInvoicePackageRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'student_id',
        'notes',
        'internal_notes',
        'start_date',
        'hourly_rate',
        'tutor_hourly_rate',
        'tutoring_location_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MonthlyInvoicePackage::class;
    }
}
