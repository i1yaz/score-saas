<?php

namespace App\Repositories;

use App\Models\MockTest;
use App\Models\MockTestStudent;
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
                'mock_test_student.notes_to_proctor','mock_test_student.signup_status','mock_test_student.extra_time','mock_tests.created_at as test_created_at',
                'mock_test_student.score','mock_test_student.score_report_type','mock_test_student.score_report_path','mock_test_student.subsection_scores',
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

    public function storeScore(Request $request, $mock_test, $student_id): bool
    {
        $data = $request->all();
        $data = array_filter($data);
        if ($request->hasFile('file') && $request->score_report_type === 'file') {
            $score_report_path = $this->storeScoreFile($request,$mock_test,$student_id);
        }elseif ($request->score_report_type === 'url'){
            $score_report_path = $request->url;
        }
        if ($request->has('english_score')){
            $data['subsection_scores']['english_score'] = $request->english_score;
        }
        if ($request->has('math_score')){
            $data['subsection_scores']['math_score'] = $request->math_score;
        }
        if ($request->has('reading_score')){
            $data['subsection_scores']['reading_score'] = $request->reading_score;
        }
        if ($request->has('science_score')){
            $data['subsection_scores']['science_score'] = $request->science_score;
        }

        $mockTestStudent = MockTestStudent::where('mock_test_id',$mock_test)->where('student_id',$student_id)->first();
        if ($mockTestStudent){
            $updateData = [
                'score' => $data['score'],
                'score_report_type' => strtoupper($data['score_report_type']),
                'score_report_path' => $score_report_path??null,
                'subsection_scores' => json_encode($data['subsection_scores'])
            ];
            MockTestStudent::where('id',$mockTestStudent->id)->update(array_filter($updateData));
            return true;
        }
        return false;
    }
    private function storeScoreFile(Request $request,$mock_test,$student_id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName ="{$mock_test}/{$student_id}/". time() . '.' . $file->getClientOriginalExtension();
            return storeFile('mock_test_score_reports',$request->file('file'), $fileName);
        }
    }
}
