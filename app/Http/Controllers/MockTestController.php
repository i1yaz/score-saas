<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMockTestRequest;
use App\Http\Requests\UpdateMockTestRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MockTestRepository;
use App\Repositories\TutoringLocationRepository;
use Illuminate\Http\Request;
use Flash;

class MockTestController extends AppBaseController
{
    /** @var MockTestRepository $mockTestRepository*/
    private $mockTestRepository;

    public function __construct(MockTestRepository $mockTestRepo)
    {
        $this->mockTestRepository = $mockTestRepo;
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

        $mockTest = $this->mockTestRepository->create($input);

        Flash::success('Mock Test saved successfully.');

        return redirect(route('mockTests.index'));
    }

    /**
     * Display the specified MockTest.
     */
    public function show($id)
    {
        $mockTest = $this->mockTestRepository->find($id);

        if (empty($mockTest)) {
            Flash::error('Mock Test not found');

            return redirect(route('mockTests.index'));
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

            return redirect(route('mockTests.index'));
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

            return redirect(route('mockTests.index'));
        }

        $mockTest = $this->mockTestRepository->update($request->all(), $id);

        Flash::success('Mock Test updated successfully.');

        return redirect(route('mockTests.index'));
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

            return redirect(route('mockTests.index'));
        }

        $this->mockTestRepository->delete($id);

        Flash::success('Mock Test deleted successfully.');

        return redirect(route('mockTests.index'));
    }

    public function locationAjax(Request $request)
    {
        $tutoringLocations = TutoringLocationRepository::getTutoringLocations($request->name);
        return response()->json($tutoringLocations->toArray());

    }
}
