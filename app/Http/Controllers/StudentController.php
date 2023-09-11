<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Controllers\AppBaseController;
use App\Mail\StudentRegistrationMail;
use App\Models\School;
use App\Models\User;
use App\Repositories\StudentRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Log;
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
        $students = $this->studentRepository->paginate(10,['students.*','u.email as email','u1.email as created_by_email']);

        return view('students.index')
            ->with('students', $students);
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
            $register = new RegisterController();
            $input['password'] = $password = \Str::password(20);
            $user = $register->register($request->merge(['password'=> $password,'password_confirmation' => $password,'userData'=>true]),false);
            $input['user_id'] = $user->id;
            $input['test_anxiety_challenge'] = $input['test_anxiety_challenge']=='yes';
            $input['testing_accommodation'] = $input['testing_accommodation']=='yes';
            $input['email_known'] = $input['email_known']=='yes';
            $input['added_by'] = \Auth::id();
            $input['added_on'] = Carbon::now();
            $this->studentRepository->create($input);
            $user->addRole('student');
            DB::commit();
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
    public function show($id)
    {
        $student = $this->studentRepository->find($id,['students.*','u.email as email','u1.email as created_by_email']);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        return view('students.show')->with('student', $student);
    }

    /**
     * Show the form for editing the specified Student.
     */
    public function edit($id)
    {
        $student = $this->studentRepository->find($id,['students.*','u.email as email','u1.email as created_by_email']);

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
        $user = User::whereHasRole('parent')
            ->select(['users.id','users.email'])
            ->join('parents','users.id','parents.user_id')
            ->where('users.email',$request->email)
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
