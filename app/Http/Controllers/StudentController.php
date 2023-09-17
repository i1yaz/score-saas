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
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class StudentController extends AppBaseController
{
    /** @var StudentRepository $studentRepository*/
    private $studentRepository;

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
        if ($request->ajax()){
            $columns = [
                'family_code',
                'email',
                'first_name',
                'last_name',
                'status',
                'action'
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
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
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
        return view('students.create',['parent'=>$request->parent??'']);
    }

    /**
     * Store a newly created Student in storage.
     */
    public function store(CreateStudentRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $passwordString = \Str::password(20);
            $register = new RegisterController();
            $input['password'] = $password = \App::environment(['production'])?Hash::make($passwordString):Hash::make('abcd1234');
            $input['password_confirmation'] = $password;
            $input['test_anxiety_challenge'] = $input['test_anxiety_challenge']=='yes';
            $input['testing_accommodation'] = $input['testing_accommodation']=='yes';
            $input['email_known'] = $input['email_known']=='yes';
            $input['added_by'] = \Auth::id();
            $input['auth_guard'] = \Auth::guard()->name;
            $input['added_on'] = Carbon::now();
            $input['status'] = $input['status']=='yes';
            $input['userData'] = true;
            $input['registrationType']='student';
            $user = $register->register($request->merge($input));
            $user->addRole('student');
            DB::commit();
            $input['password'] = $passwordString;
            Mail::to($user)->send(new StudentRegistrationMail($input));
            Flash::success('Student saved successfully.');
            return redirect(route('students.index'));
        }catch (QueryException $queryException){
            DB::rollBack();
            report($queryException);
            Flash::error('something went wrong');
            return redirect(route('students.index'));
        }

    }

    /**
     * Display the specified Student.
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);

        return view('students.show')->with('student', $student);
    }

    /**
     * Show the form for editing the specified Student.
     */
    public function edit($id)
    {
        $student = $this->studentRepository->find($id);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        return view('students.edit')->with('student', $student);
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
        $input['test_anxiety_challenge'] = $input['test_anxiety_challenge']=='yes';
        $input['testing_accommodation'] = $input['testing_accommodation']=='yes';
        $input['email_known'] = $input['email_known']=='yes';
        $input['status'] = $input['status']=='yes';
        $this->studentRepository->update($input, $id);
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

        $this->studentRepository->delete($id);

        Flash::success('Student deleted successfully.');

        return redirect(route('students.index'));
    }
    /**
     * Fetch Parents.
     *
     * @throws \Exception
     */
    public function studentParentAjax(Request $request){
        $data = [];
        $email = trim($request->email);
        $user = ParentUser::select(['parents.id','parents.email'])
            ->where('parents.email','LIKE',"%{$email}%")
            ->where('parents.status',true)
            ->first();
        if ($user){
            $data[] =
                [
                    'id' =>$user->id,
                    'text' => $user->email
                ];

        }
        return response()->json($data);
    }
    /**
     * Fetch School.
     *
     * @throws \Exception
     */
    public function studentSchoolAjax(Request $request){
        $data = [];
        $schools = School::where('name','LIKE',"%{$request->name}%")->get();
        if ($schools->isNotEmpty()){
            foreach ($schools as $school){
                $data[]= ['id'=>$school->id,'text' =>$school->name];
            }

        }
        return response()->json($data);
    }

}
