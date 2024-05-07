<?php

namespace App\Repositories;

use App\Models\MockTest;
use App\Models\Proctor;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function getMockTestDetails($mockTestId,$studentId=null)
    {
        $mockTest =  MockTest::select(
            [
                'mock_tests.id as mock_test_id','date','tutoring_locations.name as location','start_time','end_time',
                'students.first_name','students.id as student_id','students.last_name','students.email as student_email','mock_test_codes.name as mock_test_code','mock_test_codes.test_type',
                'mock_test_student.notes_to_proctor','mock_test_student.signup_status','mock_test_student.extra_time','mock_tests.created_at as test_created_at'
            ])
            ->join('tutoring_locations','tutoring_locations.id','=','mock_tests.location_id')
            ->join('mock_test_student','mock_test_student.mock_test_id','=','mock_tests.id')
            ->join('students','students.id','=','mock_test_student.student_id')
            ->join('mock_test_codes','mock_test_codes.id','=','mock_test_student.mock_test_code_id');
//            ->join('proctors','proctors.id','=','mock_tests.proctor_id')
        $mockTest = $mockTest->where('mock_tests.id',$mockTestId);
        if (!empty($studentId)) {
            $mockTest = $mockTest->where('mock_test_student.id',$studentId);
            return $mockTest->first();
        }
        return $mockTest->get();
    }

    public function storeScore(Request $request, $mock_test, $student_id)
    {
        $data = $request->all();
        $data = array_filter($data);
        $mockTestStudent = DB::table('mock_test_student')->where('mock_test_id',$mock_test)->where('student_id',$student_id)->first();
        if ($mockTestStudent){

            DB::table('mock_test_student')->where('id',$mockTestStudent->id)->update($data);
        }
        return true;
    }
}
