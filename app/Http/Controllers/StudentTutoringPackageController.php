<?php

namespace App\Http\Controllers;

use App\DataTables\StudentTutoringPackageDataTable;
use App\Http\Requests\CreateStudentTutoringPackageRequest;
use App\Http\Requests\UpdateStudentTutoringPackageRequest;
use App\Mail\ParentInvoiceMailAfterStudentTutoringPackageCreation;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Models\Subject;
use App\Models\Tutor;
use App\Models\TutoringLocation;
use App\Models\TutoringPackageType;
use App\Repositories\StudentTutoringPackageRepository;
use Flash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StudentTutoringPackageController extends AppBaseController
{
    private StudentTutoringPackageRepository $studentTutoringPackageRepository;

    public function __construct(StudentTutoringPackageRepository $studentTutoringPackageRepo)
    {
        $this->studentTutoringPackageRepository = $studentTutoringPackageRepo;
    }

    /**
     * Display a listing of the StudentTutoringPackage.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $columns = [
                'package_id',
                'student',
                'tutoring_package_type',
                'notes',
                'hours',
                'location',
                'start_date',
                'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = StudentTutoringPackageDataTable::totalRecords();
            $studentTutoringPackages = StudentTutoringPackageDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = StudentTutoringPackageDataTable::totalFilteredRecords($search);
            $data = StudentTutoringPackageDataTable::populateRecords($studentTutoringPackages);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);

        }

        return view('student_tutoring_packages.index');
    }

    /**
     * Show the form for creating a new StudentTutoringPackage.
     */
    public function create()
    {
        $subjects = Subject::get(['id', 'name']);

        return view('student_tutoring_packages.create', ['subjects' => $subjects]);
    }

    /**
     * Store a newly created StudentTutoringPackage in storage.
     */
    public function store(CreateStudentTutoringPackageRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $tutors = $input['tutor_ids'];
            $subjects = $input['subject_ids'];
            unset($input['tutor_ids'],$input['subject_ids']);
            if (empty($input['discount'])) {
                $input['discount'] = 0;
            }
            $studentTutoringPackage = $this->studentTutoringPackageRepository->create($input);
            $studentTutoringPackage->tutors()->sync($tutors);
            $studentTutoringPackage->subjects()->sync($subjects);
            $this->studentTutoringPackageRepository->createInvoiceForPackage($studentTutoringPackage,$input);
            DB::commit();
            if ($input['email_to_parent'] == 1) {
                $parentEmail = Student::select(['parents.email as parent_email','students.id','students.parent_id'])->where('students.id',$input['student_id'])
                    ->join('parents', 'students.parent_id', '=', 'parents.id')->first();
                Mail::to($parentEmail->parent_email)->send(new ParentInvoiceMailAfterStudentTutoringPackageCreation($studentTutoringPackage));
            }
            Flash::success('Student Tutoring Package saved successfully.');

            return redirect(route('student-tutoring-packages.show', ['student_tutoring_package' => $studentTutoringPackage->id]));
        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            \Laracasts\Flash\Flash::error('something went wrong');

            return redirect(route('student-tutoring-packages.index'));
        }

    }

    /**
     * Display the specified StudentTutoringPackage.
     */
    public function show($id)
    {
        $studentTutoringPackage = $this->studentTutoringPackageRepository->show($id);

        if (empty($studentTutoringPackage)) {
            Flash::error('Student Tutoring Package not found');

            return redirect(route('student-tutoring-packages.index'));
        }

        return view('student_tutoring_packages.show')->with('studentTutoringPackage', $studentTutoringPackage);
    }

    /**
     * Show the form for editing the specified StudentTutoringPackage.
     */
    public function edit($id)
    {
        $subjects = Subject::get(['id', 'name']);
        $studentTutoringPackage = StudentTutoringPackage::with(['subjects'])->find($id);
        $selectedSubjects = $studentTutoringPackage->subjects->pluck(['id'])->toArray();

        if (empty($studentTutoringPackage)) {
            Flash::error('Student Tutoring Package not found');

            return redirect(route('student-tutoring-packages.index'));
        }

        return view('student_tutoring_packages.edit')->with('studentTutoringPackage', $studentTutoringPackage)
            ->with('selectedSubjects', $selectedSubjects)
            ->with('subjects', $subjects);
    }

    /**
     * Update the specified StudentTutoringPackage in storage.
     */
    public function update($id, UpdateStudentTutoringPackageRequest $request)
    {

        $studentTutoringPackage = $this->studentTutoringPackageRepository->find($id);

        if (empty($studentTutoringPackage)) {
            Flash::error('Student Tutoring Package not found');

            return redirect(route('student-tutoring-packages.index'));
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            if (isset($input['tutor_ids']) && is_array($input['tutor_ids']) && count($input['tutor_ids']) > 0) {
                $tutors = $input['tutor_ids'];
                $studentTutoringPackage->tutors()->sync($tutors);
                unset($input['tutor_ids']);
            }
            if (isset($input['subject_ids']) && is_array($input['subject_ids']) && count($input['subject_ids']) > 0) {
                $subjects = $input['subject_ids'];
                $studentTutoringPackage->subjects()->sync($subjects);
                unset($input['subject_ids']);
            }

            $studentTutoringPackage = $this->studentTutoringPackageRepository->update($input, $id);
            DB::commit();
            Flash::success('Student Tutoring Package saved successfully.');

            return view('student_tutoring_packages.show')->with('studentTutoringPackage', $studentTutoringPackage);
        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            \Laracasts\Flash\Flash::error('something went wrong');

            return redirect(route('student-tutoring-packages.index'));
        }

        Flash::success('Student Tutoring Package updated successfully.');

        return redirect(route('student-tutoring-packages.index'));
    }

    /**
     * Remove the specified StudentTutoringPackage from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $studentTutoringPackage = $this->studentTutoringPackageRepository->find($id);

        if (empty($studentTutoringPackage)) {
            Flash::error('Student Tutoring Package not found');

            return redirect(route('student-tutoring-packages.index'));
        }

        $this->studentTutoringPackageRepository->delete($id);

        Flash::success('Student Tutoring Package deleted successfully.');

        return redirect(route('student-tutoring-packages.index'));
    }

    public function tutoringPackageTypeAjax(Request $request)
    {
        $name = trim($request->name);
        $tutoringPackageTypes = TutoringPackageType::active()
            ->select(['tutoring_package_types.id as id', 'tutoring_package_types.name as text', 'tutoring_package_types.hours'])
            ->where('tutoring_package_types.name', 'LIKE', "%{$name}%")
            ->limit(5)
            ->get();

        return response()->json($tutoringPackageTypes->toArray());
    }

    public function studentEmailAjax(Request $request)
    {
        $email = trim($request->email);
        $students = Student::active()
            ->select(['students.id as id', 'students.email as text'])
            ->where('students.email', 'LIKE', "%{$email}%")
            ->limit(5)
            ->get();

        return response()->json($students->toArray());
    }

    public function tutorEmailAjax(Request $request)
    {
        $email = trim($request->email);
        $tutors = Tutor::active()
            ->select(['tutors.id as id', 'tutors.email as text'])
            ->where('tutors.email', 'LIKE', "%{$email}%")
            ->limit(5)
            ->get();

        return response()->json($tutors->toArray());
    }

    public function tutoringLocationAjax(Request $request)
    {
        $name = trim($request->name);
        $tutoringLocations = TutoringLocation::select(['tutoring_locations.id as id', 'tutoring_locations.name as text'])
            ->where('tutoring_locations.name', 'LIKE', "%{$name}%")
            ->limit(5)
            ->get();

        return response()->json($tutoringLocations->toArray());

    }
}
