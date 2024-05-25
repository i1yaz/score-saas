<?php

namespace App\Http\Controllers;

use App\Models\ListData;
use App\Models\MockTestCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class MockTestCodeController extends Controller
{
    public function index()
    {
        $mockTestCodes = MockTestCode::join('list_data','list_data.id','mock_test_codes.test_type')
            ->select(['mock_test_codes.id','mock_test_codes.name as name','list_data.name as test_type'])
            ->paginate(20);
        return view('mock_test_codes.index')
            ->with('mockTestCodes', $mockTestCodes);
    }
    public function create()
    {
        $testTypes = ListData::select(['id','name'])
            ->where('list_id', MockTestCode::LIST_DATA_LIST_ID)
            ->pluck('name','id')
            ->toArray()??[];
        return view('mock_test_codes.create', compact('testTypes'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'test_type' => ['required'],
        ]);
        $mockTest = MockTestCode::create(array_merge($request->all(),['auth_guard' => Auth::user()->auth_guard,'added_by' => Auth::id()]));
        if ($mockTest) {
            Flash::success('Mock Test Code created successfully.');
        } else {
            Flash::error('Mock Test Code could not be created.');
        }
        return redirect()->route('mock-test-codes.index');
    }
    public function edit($id)
    {
        $testTypes = ListData::select(['id','name'])
            ->where('list_id', MockTestCode::LIST_DATA_LIST_ID)
            ->pluck('name','id')
            ->toArray()??[];
        $mockTestCode = MockTestCode::findOrFail($id);
        return view('mock_test_codes.edit')
            ->with('mockTestCode', $mockTestCode)
            ->with('testTypes', $testTypes);
    }
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'test_type' => ['required'],
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
