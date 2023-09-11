<?php

namespace App\Repositories;

use App\Models\School;
use App\Repositories\BaseRepository;

class SchoolRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'address'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return School::class;
    }
}
