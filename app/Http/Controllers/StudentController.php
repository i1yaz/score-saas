<?php

namespace App\Http\Controllers;

use App\DataTables\StudentsDataTable;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Mail\StudentRegistrationMail;
use App\Models\ParentUser;
use App\Models\School;
use App\Models\Student;
use App\Repositories\StudentRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;

class StudentController extends AppBaseController
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepo)
    {
        $this->studentRepository = $studentRepo;
    }

    /**
     * Display a listing of the Student.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);
        if ($request->ajax()) {
            $columns = [
                'family_code',
                'email',
                'first_name',
                'last_name',
                'status',
                'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = StudentsDataTable::totalRecords();
            $students = StudentsDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = StudentsDataTable::totalFilteredRecords($search);
            $data = StudentsDataTable::populateRecords($students);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);

        }

        return view('students.index');
    }

    /**
     * Show the form for creating a new Student.
     */
    public function create(Request $request)
    {
        return view('students.create', ['parent' => $request->parent ?? '']);
    }

    /**
     * Store a newly created Student in storage.
     */
    public function store(CreateStudentRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $passwordString = Str::password(20);
            $register = new RegisterController();
            $input['password'] = $password = App::environment(['production']) ? Hash::make($passwordString) : Hash::make('abcd1234');
            $input['password_confirmation'] = $password;
            $input['test_anxiety_challenge'] = $input['test_anxiety_challenge'] == 'yes';
            $input['testing_accommodation'] = $input['testing_accommodation'] == 'yes';
            $input['email_known'] = $input['email_known'] == 'yes';
            $input['added_by'] = Auth::id();
            $input['auth_guard'] = Auth::guard()->name;
            $input['status'] = $input['status'] == 'yes';
            $input['userData'] = true;
            $input['registrationType'] = 'student';
            $user = $register->register($request->merge($input), false);
            $user->addRole('student');
            DB::commit();
            $input['password'] = App::environment(['production']) ? $passwordString : 'abcd1234';
            Mail::to($user)->send(new StudentRegistrationMail($input));
            if ($request->ajax()){
                return response()->json(['success' => true, 'message' => 'Student saved successfully.','redirectTo' => route('students.index')]);
            }
            Flash::success('Student saved successfully.');
            return redirect(route('students.index'));
        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            Flash::error('something went wrong');

            return redirect(route('students.index'));
        }

    }

    /**
     * Display the specified Student.
     */
    public function show($student)
    {
        $student = Student::select(['students.id as student_id', 'students.email as student_email', 'students.first_name as first_name',
            'students.last_name as last_name', 'students.status as status', 'parents.email as parent_email', 'schools.name as school_name',
            'students.testing_accommodation', 'students.testing_accommodation_nature', 'students.official_baseline_act_score', 'students.official_baseline_sat_score',
            'students.test_anxiety_challenge', 'students.created_at as student_created_at',
        ])
            ->where('students.id', $student)
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id')
            ->leftJoin('schools', 'students.school_id', '=', 'schools.id')
            ->first();
        $this->authorize('view', $student);

        return view('students.show')->with('student', $student);
    }

    /**
     * Show the form for editing the specified Student.
     */
    public function edit($id)
    {
        $student = Student::query()
            ->select(
                [
                    'students.*','parents.id as parent_id','parents.email as parent_email',
                    'schools.id as school_id','schools.name as school_name'
                ]
            )
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id')
            ->join('schools', 'students.school_id', '=', 'schools.id')
            ->find($id);
        $selectedParent[$student->parent_id] = $student->parent_email;
        $selectedSchool[$student->school_id] = $student->school_name;
        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        return view('students.edit')
            ->with('student', $student)
            ->with('selectedSchool', $selectedSchool)
            ->with('selectedParent', $selectedParent);
    }

    /**
     * Update the specified Student in storage.
     */
    public function update($id, UpdateStudentRequest $request)
    {
        $student = $this->studentRepository->find($id);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        $input = $request->all();
        $input['test_anxiety_challenge'] = $input['test_anxiety_challenge'] == 'yes';
        $input['testing_accommodation'] = $input['testing_accommodation'] == 'yes';
        $input['email_known'] = $input['email_known'] == 'yes';
        $input['status'] = $input['status'] == 'yes';
        $input = array_filter($input);
        $this->studentRepository->update($input, $id);
        if ($request->ajax()){
            return response()->json(['success' => true, 'message' => 'Student saved successfully.','redirectTo' => route('students.index')]);
        }
        Flash::success('Student updated successfully.');
        return redirect(route('students.index'));
    }

    /**
     * Remove the specified Student from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $student = $this->studentRepository->find($id);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        $this->studentRepository->toggleStatus($id);

        Flash::success('Student deleted successfully.');

        return redirect(route('students.index'));
    }

    /**
     * Fetch Parents.
     *
     * @throws \Exception
     */
    public function studentParentAjax(Request $request)
    {
        $email = trim($request->email);
        $user = ParentUser::select(['parents.id as id', 'parents.email as text'])
            ->active()
            ->where('parents.email', 'LIKE', "%{$email}%")
            ->limit(5)
            ->get();

        return response()->json($user->toArray());
    }

    /**
     * Fetch School.
     *
     * @throws \Exception
     */
    public function studentSchoolAjax(Request $request)
    {
        $name = trim($request->name);
        $schools = School::select(['schools.id as id', 'schools.name as text'])
            ->active()
            ->where('schools.name', 'LIKE', "%{$name}%")
            ->where('status', true)
            ->get();

        return response()->json($schools->toArray());
    }
}
