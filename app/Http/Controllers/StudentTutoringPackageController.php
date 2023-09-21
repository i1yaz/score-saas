<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentTutoringPackageRequest;
use App\Http\Requests\UpdateStudentTutoringPackageRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\PackageType;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\TutoringLocation;
use App\Repositories\StudentTutoringPackageRepository;
use Illuminate\Http\Request;
use Flash;

class StudentTutoringPackageController extends AppBaseController
{
    /** @var StudentTutoringPackageRepository $studentTutoringPackageRepository*/
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
        $studentTutoringPackages = $this->studentTutoringPackageRepository->paginate(10);

        return view('student_tutoring_packages.index')
            ->with('studentTutoringPackages', $studentTutoringPackages);
    }

    /**
     * Show the form for creating a new StudentTutoringPackage.
     */
    public function create()
    {
        return view('student_tutoring_packages.create');
    }

    /**
     * Store a newly created StudentTutoringPackage in storage.
     */
    public function store(CreateStudentTutoringPackageRequest $request)
    {
        $input = $request->all();

        $studentTutoringPackage = $this->studentTutoringPackageRepository->create($input);

        Flash::success('Student Tutoring Package saved successfully.');

        return redirect(route('student-tutoring-packages.index'));
    }

    /**
     * Display the specified StudentTutoringPackage.
     */
    public function show($id)
    {
        $studentTutoringPackage = $this->studentTutoringPackageRepository->find($id);

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
        $studentTutoringPackage = $this->studentTutoringPackageRepository->find($id);

        if (empty($studentTutoringPackage)) {
            Flash::error('Student Tutoring Package not found');

            return redirect(route('student-tutoring-packages.index'));
        }

        return view('student_tutoring_packages.edit')->with('studentTutoringPackage', $studentTutoringPackage);
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

        $studentTutoringPackage = $this->studentTutoringPackageRepository->update($request->all(), $id);

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

    public function packageTypeAjax(Request $request){
        $name = trim($request->name);
        $packageTypes = PackageType::active()
            ->select(['package_types.id as id','package_types.name as text','package_types.hours'])
            ->where('package_types.name','LIKE',"%{$name}%")
            ->limit(5)
            ->get();
        return response()->json($packageTypes->toArray());
    }
    public function studentEmailAjax(Request $request){
        $email = trim($request->email);
        $students = Student::active()
            ->select(['students.id as id', 'students.email as text'])
            ->where('students.email', 'LIKE', "%{$email}%")
            ->limit(5)
            ->get();
        return response()->json($students->toArray());
    }
    public function tutorEmailAjax(Request $request){
        $email = trim($request->email);
        $parents = ParentUser::active()
            ->select(['parents.id as id', 'parents.email as text'])
            ->where('parents.email', 'LIKE', "%{$email}%")
            ->limit(5)
            ->get();

        return response()->json($parents->toArray());
    }
    public function tutoringLocationAjax(Request $request){
        $name = trim($request->name);
        $tutoringLocations = TutoringLocation::select(['tutoring_locations.id as id','tutoring_locations.name as text'])
            ->where('tutoring_locations.name','LIKE', "%{$name}%")
            ->limit(5)
            ->get();
        return response()->json($tutoringLocations->toArray());

    }
}
