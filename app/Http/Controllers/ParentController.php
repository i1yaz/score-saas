<?php

namespace App\Http\Controllers;

use App\DataTables\ParentsDataTable;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Mail\ParentRegisteredMail;
use App\Models\ParentUser;
use App\Repositories\ParentRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;

class ParentController extends AppBaseController
{
    private ParentRepository $parentRepository;

    public function __construct(ParentRepository $parentRepo)
    {
        $this->parentRepository = $parentRepo;
    }

    /**
     * Display a listing of the Parent.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'family_code',
                'email',
                'first_name',
                'last_name',
                'phone',
                'status',
                'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = ParentsDataTable::totalRecords();
            $parents = ParentsDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = ParentsDataTable::totalFilteredRecords($search);
            $data = ParentsDataTable::populateRecords($parents);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);

        }

        return view('parents.index');
    }

    /**
     * Show the form for creating a new Parent.
     */
    public function create()
    {
        return view('parents.create');
    }

    /**
     * Store a newly created Parent in storage.
     */
    public function store(CreateParentRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $passwordString = Str::password(20);
            $register = new RegisterController();
            $input['password'] = $password = App::environment(['production']) ? Hash::make($passwordString) : Hash::make('abcd1234');
            $input['password_confirmation'] = $password;
            $input['first_name'] = $request['first_name'];
            $input['last_name'] = $request['last_name'];
            $input['email'] = $request['email'];
            $input['auth_guard'] = Auth::guard()->name;
            $input['added_by'] = Auth::id();
            $input['referral_from_positive_experience_with_tutor'] = $input['referral_from_positive_experience_with_tutor'] == 'yes';
            $input['status'] = $input['status'] == 'yes';
            $input['userData'] = true;
            $input['registrationType'] = 'parent';
            $user = $register->register($request->merge($input), false);
            $user->addRole('parent');
            DB::commit();
            $input['password'] = App::environment(['production']) ? $passwordString : 'abcd1234';
            try {
                Mail::to($user)->send(new ParentRegisteredMail($input));

            } catch (\Exception $e) {
                report($e);
            }
            Flash::success('Parent saved successfully.');

            return redirect(route('parents.index'));

        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            Flash::error('something went wrong');

            return redirect(route('parents.index'));
        }

    }

    /**
     * Display the specified Parent.
     */
    public function show(ParentUser $parent)
    {
        $this->authorize('view', $parent);
        return view('parents.show')->with('parent', $parent);
    }

    /**
     * Show the form for editing the specified Parent.
     */
    public function edit($id)
    {
        $parent = ParentUser::select(['parents.*','students.parent_id as parent_id'])
            ->join('students', 'students.parent_id', 'parents.id');
        if (Auth::user()->hasRole('student')) {
            $parent = $parent->where('students.id', Auth::id());
        }
        $parent = $parent->findOrFail($id);
        $this->authorize('update', $parent);

        return view('parents.edit')->with('parent', $parent);
    }

    /**
     * Update the specified Parent in storage.
     */
    public function update(ParentUser $parent, UpdateParentRequest $request)
    {
        $this->authorize('update', $parent);

        $input = $request->all();
        $input['referral_from_positive_experience_with_tutor'] = $input['referral_from_positive_experience_with_tutor'] == 'yes';
        $input['status'] = $input['status'] == 'yes';
        $this->parentRepository->update($input, $parent->id);
        Flash::success('Parent updated successfully.');

        return redirect(route('parents.index'));
    }

    /**
     * Remove the specified Parent from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $parent = ParentUser::findOrFail($id);
        if (! $parent) {
            Flash::error('No record found.');

            return redirect(route('parents.index'));
        }

        $this->parentRepository->toggleStatus($id);

        Flash::success('Parent deleted successfully.');

        return redirect(route('parents.index'));
    }
}
