<?php

namespace App\Repositories;

use App\Models\MockTest;
use App\Models\Proctor;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;
use PhpParser\Builder\Class_;

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

    public function storeMockTest($input)
    {
        list($input['proctorable_type'], $input['proctorable_id']) = explode('_',  $input['proctor_id']);
        if ($input['proctorable_type']==='proctor') {
            $input['proctorable_type'] = Proctor::class;
            $input['proctor_id'] = $input['proctorable_id'];
        }
        return $this->create(array_filter($input));
    }
    public function addStudents($array)
    {
        try {
            $mockTest = $this->find($array['mock_test_id']);
            $mockTest->students()->attach($array['student_id'],['mock_test_code_id' => $array['mock_test_code_id'], 'notes_to_proctor' => $array['notes_to_proctor']]);
            return true;
        }catch (\Exception $e) {
            Log::critical($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return false;
        }

    }
}
