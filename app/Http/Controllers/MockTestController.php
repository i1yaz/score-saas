<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\AddMockTestStudentRequest;
use App\Http\Requests\CreateMockTestRequest;
use App\Http\Requests\UpdateMockTestRequest;
use App\Models\MockTest;
use App\Models\MockTestCode;
use App\Models\Student;
use App\Repositories\MockTestCodeRepository;
use App\Repositories\MockTestRepository;
use App\Repositories\ProctorRepository;
use App\Repositories\StudentRepository;
use App\Repositories\TutoringLocationRepository;
use Illuminate\Http\Request;
use Flash;

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

        Flash::success('Mock Test saved successfully.');

        return redirect(route('mock-tests.index'));
    }

    /**
     * Display the specified MockTest.
     */
    public function show($id)
    {
        $mockTest = MockTest::with(['students'])->find($id);

        if (empty($mockTest)) {
            Flash::error('Mock Test not found');

            return redirect(route('mock-tests.index'));
        }

        return view('mock_tests.show')->with('mockTest', $mockTest);
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
}
