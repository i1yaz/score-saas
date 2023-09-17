<?php

namespace App\Repositories;

use App\Models\Tutor;
use App\Repositories\BaseRepository;

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
        'start_date'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Tutor::class;
    }
}
