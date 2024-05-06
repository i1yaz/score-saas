<?php

namespace App\Repositories;

use App\Models\MockTestCode;
use App\Models\Student;
use App\Repositories\BaseRepository;

class MockTestCodeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'code',
        'mock_test_id',
        'student_id',
        'status'
    ];
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MockTestCode::class;
    }

    public function getMockTestCodes($name,$limit = 5)
    {
        $name = trim($name);
        return MockTestCode::select(['id as id', 'name as text'])
            ->where('name', 'LIKE', "%{$name}%")
            ->limit($limit)
            ->get();
    }
}
