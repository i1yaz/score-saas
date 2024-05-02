<?php

namespace App\Http\Controllers;

use App\Models\MockTestCode;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class MockTestCodeController extends Controller
{
    public function index()
    {
        $mockTestCodes = MockTestCode::paginate(20);
        return view('mock_test_codes.index')
            ->with('mockTestCodes', $mockTestCodes);
    }
    public function create()
    {
        $testTypes = ['SAT', 'ACT'];
        return view('mock_test_codes.create', compact('testTypes'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'test_type' => ['required', 'in:SAT,ACT'],
        ]);
        $mockTest = MockTestCode::create($request->all());
        if ($mockTest) {
            Flash::success('Mock Test Code created successfully.');
        } else {
            Flash::error('Mock Test Code could not be created.');
        }
        return redirect()->route('mock-test-codes.index');
    }
    public function edit($id)
    {
        $testTypes = ['SAT', 'ACT'];
        $mockTestCode = MockTestCode::findOrFail($id);
        return view('mock_test_codes.edit')
            ->with('mockTestCode', $mockTestCode)
            ->with('testTypes', $testTypes);
    }
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'test_type' => ['required', 'in:SAT,ACT'],
        ]);
        $mockTestCode = MockTestCode::findOrFail($id);
        $mockTestCode = $mockTestCode->update($request->all());
        if ($mockTestCode) {
            Flash::success('Mock Test Code updated successfully.');
        } else {
            Flash::error('Mock Test Code could not be updated.');
        }
        return redirect()->route('mock-test-codes.index');
    }

    public function destroy($id)
    {
        $mockTestCode = MockTestCode::findOrFail($id);
        $mockTestCode->delete();
        Flash::success('Mock Test Code deleted successfully.');
        return redirect()->route('mock-test-codes.index');
    }
}
