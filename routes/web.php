<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Route::get('packages',[App\Http\Controllers\SchoolController::class,'index'])->name('packages.index')->middleware(['permission:package-index']);
//Route::get('packages/create',[App\Http\Controllers\SchoolController::class,'create'])->name('packages.create')->middleware(['permission:package-create']);
//Route::post('packages',[App\Http\Controllers\SchoolController::class,'store'])->name('packages.store')->middleware(['permission:package-create']);;
//Route::get('packages/{package}',[App\Http\Controllers\SchoolController::class,'show'])->name('packages.show')->middleware(['permission:package-show']);
//Route::get('packages/{package}/edit',[App\Http\Controllers\SchoolController::class,'edit'])->name('packages.edit')->middleware(['permission:package-edit']);
//Route::patch('packages/{package}',[App\Http\Controllers\SchoolController::class,'update'])->name('packages.update')->middleware(['permission:package-edit']);
//Route::delete('packages/{package}',[App\Http\Controllers\SchoolController::class,'destroy'])->name('packages.destroy')->middleware(['permission:package-destroy']);

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);
Route::get('admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');

Route::group(['middleware' => ['auth:web,parent,student,tutor']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    //Parent
    Route::get('parents', [App\Http\Controllers\ParentController::class, 'index'])->name('parents.index')->middleware(['permission:parent-index']);
    Route::get('parents/create', [App\Http\Controllers\ParentController::class, 'create'])->name('parents.create')->middleware(['permission:parent-create']);
    Route::post('parents', [App\Http\Controllers\ParentController::class, 'store'])->name('parents.store')->middleware(['permission:parent-create']);
    Route::get('parents/{parent}', [App\Http\Controllers\ParentController::class, 'show'])->name('parents.show')->middleware(['permission:parent-show']);
    Route::get('parents/{parent}/edit', [App\Http\Controllers\ParentController::class, 'edit'])->name('parents.edit')->middleware(['permission:parent-edit']);
    Route::patch('parents/{parent}', [App\Http\Controllers\ParentController::class, 'update'])->name('parents.update')->middleware(['permission:parent-edit']);
    Route::delete('parents/{parent}', [App\Http\Controllers\ParentController::class, 'destroy'])->name('parents.destroy')->middleware(['permission:parent-destroy']);
    //Student
    Route::get('students', [App\Http\Controllers\StudentController::class, 'index'])->name('students.index')->middleware(['permission:student-index']);
    Route::get('students/create', [App\Http\Controllers\StudentController::class, 'create'])->name('students.create')->middleware(['permission:student-create']);
    Route::post('students', [App\Http\Controllers\StudentController::class, 'store'])->name('students.store')->middleware(['permission:student-create']);
    Route::get('students/{parent}', [App\Http\Controllers\StudentController::class, 'show'])->name('students.show')->middleware(['permission:student-show']);
    Route::get('students/{parent}/edit', [App\Http\Controllers\StudentController::class, 'edit'])->name('students.edit')->middleware(['permission:student-edit']);
    Route::patch('students/{parent}', [App\Http\Controllers\StudentController::class, 'update'])->name('students.update')->middleware(['permission:student-edit']);
    Route::delete('students/{parent}', [App\Http\Controllers\StudentController::class, 'destroy'])->name('students.destroy')->middleware(['permission:student-destroy']);
    Route::get('student-parent-ajax', [App\Http\Controllers\StudentController::class, 'studentParentAjax'])->name('student-parent-ajax')->middleware(['permission:student-create']);
    Route::get('student-school-ajax', [App\Http\Controllers\StudentController::class, 'studentSchoolAjax'])->name('student-school-ajax')->middleware(['permission:student-create']);
    //School
    Route::get('schools', [App\Http\Controllers\SchoolController::class, 'index'])->name('schools.index')->middleware(['permission:school-index']);
    Route::get('schools/create', [App\Http\Controllers\SchoolController::class, 'create'])->name('schools.create')->middleware(['permission:school-create']);
    Route::post('schools', [App\Http\Controllers\SchoolController::class, 'store'])->name('schools.store')->middleware(['permission:school-create']);
    Route::get('schools/{school}', [App\Http\Controllers\SchoolController::class, 'show'])->name('schools.show')->middleware(['permission:school-show']);
    Route::get('schools/{school}/edit', [App\Http\Controllers\SchoolController::class, 'edit'])->name('schools.edit')->middleware(['permission:school-edit']);
    Route::patch('schools/{school}', [App\Http\Controllers\SchoolController::class, 'update'])->name('schools.update')->middleware(['permission:school-edit']);
    Route::delete('schools/{school}', [App\Http\Controllers\SchoolController::class, 'destroy'])->name('schools.destroy')->middleware(['permission:school-destroy']);
    //Tutor
    Route::get('tutors', [App\Http\Controllers\TutorController::class, 'index'])->name('tutors.index')->middleware(['permission:tutor-index']);
    Route::get('tutors/create', [App\Http\Controllers\TutorController::class, 'create'])->name('tutors.create')->middleware(['permission:tutor-create']);
    Route::post('tutors', [App\Http\Controllers\TutorController::class, 'store'])->name('tutors.store')->middleware(['permission:tutor-create']);
    Route::get('tutors/{tutor}', [App\Http\Controllers\TutorController::class, 'show'])->name('tutors.show')->middleware(['permission:tutor-show']);
    Route::get('tutors/{tutor}/edit', [App\Http\Controllers\TutorController::class, 'edit'])->name('tutors.edit')->middleware(['permission:tutor-edit']);
    Route::patch('tutors/{tutor}', [App\Http\Controllers\TutorController::class, 'update'])->name('tutors.update')->middleware(['permission:tutor-edit']);
    Route::delete('tutors/{tutor}', [App\Http\Controllers\TutorController::class, 'destroy'])->name('tutors.destroy')->middleware(['permission:tutor-destroy']);
    //Package Types
    Route::get('package-types', [App\Http\Controllers\PackageTypeController::class, 'index'])->name('package-types.index')->middleware(['permission:package_type-index']);
    Route::get('package-types/create', [App\Http\Controllers\PackageTypeController::class, 'create'])->name('package-types.create')->middleware(['permission:package_type-create']);
    Route::post('package-types', [App\Http\Controllers\PackageTypeController::class, 'store'])->name('package-types.store')->middleware(['permission:package_type-create']);
    Route::get('package-types/{package_type}', [App\Http\Controllers\PackageTypeController::class, 'show'])->name('package-types.show')->middleware(['permission:package_type-show']);
    Route::get('package-types/{package_type}/edit', [App\Http\Controllers\PackageTypeController::class, 'edit'])->name('package-types.edit')->middleware(['permission:package_type-edit']);
    Route::patch('package-types/{package_type}', [App\Http\Controllers\PackageTypeController::class, 'update'])->name('package-types.update')->middleware(['permission:package_type-edit']);
    Route::delete('package-types/{package_type}', [App\Http\Controllers\PackageTypeController::class, 'destroy'])->name('package-types.destroy')->middleware(['permission:package_type-destroy']);
    //Subjects
    Route::get('subjects',[App\Http\Controllers\SubjectController::class,'index'])->name('subjects.index')->middleware(['permission:subject-index']);
    Route::get('subjects/create',[App\Http\Controllers\SubjectController::class,'create'])->name('subjects.create')->middleware(['permission:subject-create']);
    Route::post('subjects',[App\Http\Controllers\SubjectController::class,'store'])->name('subjects.store')->middleware(['permission:subject-create']);;
    Route::get('subjects/{subject}',[App\Http\Controllers\SubjectController::class,'show'])->name('subjects.show')->middleware(['permission:subject-show']);
    Route::get('subjects/{subject}/edit',[App\Http\Controllers\SubjectController::class,'edit'])->name('subjects.edit')->middleware(['permission:subject-edit']);
    Route::patch('subjects/{subject}',[App\Http\Controllers\SubjectController::class,'update'])->name('subjects.update')->middleware(['permission:subject-edit']);
    Route::delete('subjects/{subject}',[App\Http\Controllers\SubjectController::class,'destroy'])->name('subjects.destroy')->middleware(['permission:subject-destroy']);
    //Tutoring Locations
    Route::get('tutoring-locations',[App\Http\Controllers\TutoringLocationController::class,'index'])->name('tutoring-locations.index')->middleware(['permission:tutoring_location-index']);
    Route::get('tutoring-locations/create',[App\Http\Controllers\TutoringLocationController::class,'create'])->name('tutoring-locations.create')->middleware(['permission:tutoring_location-create']);
    Route::post('tutoring-locations',[App\Http\Controllers\TutoringLocationController::class,'store'])->name('tutoring-locations.store')->middleware(['permission:tutoring_location-create']);;
    Route::get('tutoring-locations/{tutoring_location}',[App\Http\Controllers\TutoringLocationController::class,'show'])->name('tutoring-locations.show')->middleware(['permission:tutoring_location-show']);
    Route::get('tutoring-locations/{tutoring_location}/edit',[App\Http\Controllers\TutoringLocationController::class,'edit'])->name('tutoring-locations.edit')->middleware(['permission:tutoring_location-edit']);
    Route::patch('tutoring-locations/{tutoring_location}',[App\Http\Controllers\TutoringLocationController::class,'update'])->name('tutoring-locations.update')->middleware(['permission:tutoring_location-edit']);
    Route::delete('tutoring-locations/{tutoring_location}',[App\Http\Controllers\TutoringLocationController::class,'destroy'])->name('tutoring-locations.destroy')->middleware(['permission:tutoring_location-destroy']);
    //Packages
//    Route::get('packages',[App\Http\Controllers\SchoolController::class,'index'])->name('packages.index')->middleware(['permission:package-index']);
//    Route::get('packages/create',[App\Http\Controllers\SchoolController::class,'create'])->name('packages.create')->middleware(['permission:package-create']);
//    Route::post('packages',[App\Http\Controllers\SchoolController::class,'store'])->name('packages.store')->middleware(['permission:package-create']);;
//    Route::get('packages/{package}',[App\Http\Controllers\SchoolController::class,'show'])->name('packages.show')->middleware(['permission:package-show']);
//    Route::get('packages/{package}/edit',[App\Http\Controllers\SchoolController::class,'edit'])->name('packages.edit')->middleware(['permission:package-edit']);
//    Route::patch('packages/{package}',[App\Http\Controllers\SchoolController::class,'update'])->name('packages.update')->middleware(['permission:package-edit']);
//    Route::delete('packages/{package}',[App\Http\Controllers\SchoolController::class,'destroy'])->name('packages.destroy')->middleware(['permission:package-destroy']);
});

