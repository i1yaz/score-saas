<?php

namespace App\Repositories;

use App\Models\MockTest;
use App\Repositories\BaseRepository;

class MockTestRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'date',
        'location_id',
        'proctor_id',
        'start_time',
        'end_time'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MockTest::class;
    }
}
