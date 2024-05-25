<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\AddMockTestStudentRequest;
use App\Http\Requests\CreateMockTestRequest;
use App\Http\Requests\CreateMockTestScoreRequest;
use App\Http\Requests\UpdateMockTestRequest;
use App\Models\MockTest;
use App\Models\MockTestCode;
use App\Models\Student;
use App\Repositories\MockTestCodeRepository;
use App\Repositories\MockTestRepository;
use App\Repositories\ProctorRepository;
use App\Repositories\StudentRepository;
use App\Repositories\TutoringLocationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;

class MockTestController extends AppBaseController
{
    /** @var MockTestRepository $mockTestRepository*/
    private MockTestRepository $mockTestRepository;
    private StudentRepository $studentRepository;
    private MockTestCodeRepository $mockTestCodeRepository;

    public function __construct(MockTestRepository $mockTestRepo,StudentRepository $studentRepos,MockTestCodeRepository $mockTestCodeRepo)
    {
        $this->mockTestRepository = $mockTestRepo;
        $this->studentRepository = $studentRepos;
        $this->mockTestCodeRepository = $mockTestCodeRepo;
    }

    /**
     * Display a listing of the MockTest.
     */
    public function index(Request $request)
    {
        if ($request->has(['start', 'end'])) {
            $events = $this->mockTestRepository->getFullCalenderEvents($request);

            return response()->json($events);
        }
        $mockTests = $this->mockTestRepository->paginate(10);

        return view('mock_tests.index')
            ->with('mockTests', $mockTests);
    }

    /**
     * Show the form for creating a new MockTest.
     */
    public function create()
    {
        return view('mock_tests.create');
    }

    /**
     * Store a newly created MockTest in storage.
     */
    public function store(CreateMockTestRequest $request)
    {
        $input = $request->all();

        $mockTest = $this->mockTestRepository->storeMockTest($input);
        if ($request->ajax()){
            return response()->json(['success' => true, 'message' => 'Mock test saved successfully.']);
        }

        Flash::success('Mock Test saved successfully.');

        return redirect(route('mock-tests.index'));
    }

    /**
     * Display the specified MockTest.
     */
    public function show($id)
    {
        if (request()->ajax()) {
            $mockTestDetail = $this->mockTestRepository->showMockTestDetail($id);
            $mockTestDetail = $mockTestDetail->toArray();
            $mockTestDetail['scheduled_date'] = Carbon::parse($mockTestDetail['date'])->format('Y-m-d');
            $mockTestDetail['location'] = $mockTestDetail['location']??'';
            $mockTestDetail['proctor'] = $mockTestDetail['proctor_email']??$mockTestDetail['tutor_email']??'';
            $mockTestDetail['created_by'] = $mockTestDetail['created_by_name']??'';

            return response()->json($mockTestDetail);
        }else{
            $mockTestDetail = $this->mockTestRepository->getMockTestDetails($id);
        }
        if ($mockTestDetail->isEmpty()) {
            Flash::error('Mock Test not found');
            return redirect(route('mock-tests.index'));
        }
        $this->authorize('view', $mockTestDetail[0]??new MockTest());
        return view('mock_tests.show')->with('mockTestDetail', $mockTestDetail);
    }

    /**
     * Show the form for editing the specified MockTest.
     */
    public function edit($id)
    {
        $mockTest = $this->mockTestRepository->find($id);

        if (empty($mockTest)) {
            Flash::error('Mock Test not found');

            return redirect(route('mock-tests.index'));
        }

        return view('mock_tests.edit')->with('mockTest', $mockTest);
    }

    /**
     * Update the specified MockTest in storage.
     */
    public function update($id, UpdateMockTestRequest $request)
    {
        $mockTest = $this->mockTestRepository->find($id);

        if (empty($mockTest)) {
            Flash::error('Mock Test not found');

            return redirect(route('mock-tests.index'));
        }

        $mockTest = $this->mockTestRepository->update($request->all(), $id);

        Flash::success('Mock Test updated successfully.');

        return redirect(route('mock-tests.index'));
    }

    /**
     * Remove the specified MockTest from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $mockTest = $this->mockTestRepository->find($id);

        if (empty($mockTest)) {
            Flash::error('Mock Test not found');

            return redirect(route('mock-tests.index'));
        }

        $this->mockTestRepository->delete($id);

        Flash::success('Mock Test deleted successfully.');

        return redirect(route('mock-tests.index'));
    }

    public function locationAjax(Request $request)
    {
        $tutoringLocations = TutoringLocationRepository::getTutoringLocations($request->name);
        return response()->json($tutoringLocations->toArray());

    }
    public function addStudents(AddMockTestStudentRequest $request)
    {
        if ($this->mockTestRepository->addStudents($request->all())){
            return response()->json(['success' => 'Student added successfully']);
        }
        return response()->json(['error' => 'Something went wrong.please try again']);
    }

    public function getStudentsAjax(Request $request){
        $email = trim($request->email);
        $students = $this->studentRepository->getStudents($email);
        return response()->json($students->toArray());
    }

    public function geMockTestCodesAjax(Request $request)
    {
        $name = trim($request->code);
        $students = $this->mockTestCodeRepository->getMockTestCodes($name);
        return response()->json($students->toArray());
    }
    public function getScore($mock_test,$student_id)
    {
        $mockTestStudent = $this->mockTestRepository->getMockTestDetails($mock_test,$student_id);
        $customFields = $this->mockTestRepository->getCustomFields($mockTestStudent);
        $subsection_scores = json_decode($mockTestStudent->subsection_scores??'{}',true);
        foreach ($subsection_scores as $key => $value){
            $mockTestStudent->$key = $value;
        }

        return view('mock_tests.add_score')
            ->with('mockTestStudent',$mockTestStudent)
            ->with('customFields',$customFields);
    }
    public function storeScore(CreateMockTestScoreRequest $request,$mock_test,$student_id)
    {
        $mockTestStudent = $this->mockTestRepository->storeScore($request,$mock_test,$student_id);
        if ($mockTestStudent){
            Flash::success('Score saved successfully');
        }else{
            Flash::error('Something went wrong. Please try again');
        }
        return redirect(route('mock-tests.show',$mock_test));
    }
}
