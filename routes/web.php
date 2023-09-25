<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePackageTypeController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentTutoringPackageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\TutoringLocationController;
use App\Http\Controllers\TutoringPackageTypeController;
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
    Route::get('parents', [ParentController::class, 'index'])->name('parents.index')->middleware(['permission:parent-index']);
    Route::get('parents/create', [ParentController::class, 'create'])->name('parents.create')->middleware(['permission:parent-create']);
    Route::post('parents', [ParentController::class, 'store'])->name('parents.store')->middleware(['permission:parent-create']);
    Route::get('parents/{parent}', [ParentController::class, 'show'])->name('parents.show')->middleware(['permission:parent-show']);
    Route::get('parents/{parent}/edit', [ParentController::class, 'edit'])->name('parents.edit')->middleware(['permission:parent-edit']);
    Route::patch('parents/{parent}', [ParentController::class, 'update'])->name('parents.update')->middleware(['permission:parent-edit']);
    Route::delete('parents/{parent}', [ParentController::class, 'destroy'])->name('parents.destroy')->middleware(['permission:parent-destroy']);
    //Student
    Route::get('students', [StudentController::class, 'index'])->name('students.index')->middleware(['permission:student-index']);
    Route::get('students/create', [StudentController::class, 'create'])->name('students.create')->middleware(['permission:student-create']);
    Route::post('students', [StudentController::class, 'store'])->name('students.store')->middleware(['permission:student-create']);
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show')->middleware(['permission:student-show']);
    Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit')->middleware(['permission:student-edit']);
    Route::patch('students/{student}', [StudentController::class, 'update'])->name('students.update')->middleware(['permission:student-edit']);
    Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy')->middleware(['permission:student-destroy']);
    Route::get('student-parent-ajax', [StudentController::class, 'studentParentAjax'])->name('student-parent-ajax')->middleware(['permission:student-create']);
    Route::get('student-school-ajax', [StudentController::class, 'studentSchoolAjax'])->name('student-school-ajax')->middleware(['permission:student-create']);
    //School
    Route::get('schools', [SchoolController::class, 'index'])->name('schools.index')->middleware(['permission:school-index']);
    Route::get('schools/create', [SchoolController::class, 'create'])->name('schools.create')->middleware(['permission:school-create']);
    Route::post('schools', [SchoolController::class, 'store'])->name('schools.store')->middleware(['permission:school-create']);
    Route::get('schools/{school}', [SchoolController::class, 'show'])->name('schools.show')->middleware(['permission:school-show']);
    Route::get('schools/{school}/edit', [SchoolController::class, 'edit'])->name('schools.edit')->middleware(['permission:school-edit']);
    Route::patch('schools/{school}', [SchoolController::class, 'update'])->name('schools.update')->middleware(['permission:school-edit']);
    Route::delete('schools/{school}', [SchoolController::class, 'destroy'])->name('schools.destroy')->middleware(['permission:school-destroy']);
    //Tutor
    Route::get('tutors', [TutorController::class, 'index'])->name('tutors.index')->middleware(['permission:tutor-index']);
    Route::get('tutors/create', [TutorController::class, 'create'])->name('tutors.create')->middleware(['permission:tutor-create']);
    Route::post('tutors', [TutorController::class, 'store'])->name('tutors.store')->middleware(['permission:tutor-create']);
    Route::get('tutors/{tutor}', [TutorController::class, 'show'])->name('tutors.show')->middleware(['permission:tutor-show']);
    Route::get('tutors/{tutor}/edit', [TutorController::class, 'edit'])->name('tutors.edit')->middleware(['permission:tutor-edit']);
    Route::patch('tutors/{tutor}', [TutorController::class, 'update'])->name('tutors.update')->middleware(['permission:tutor-edit']);
    Route::delete('tutors/{tutor}', [TutorController::class, 'destroy'])->name('tutors.destroy')->middleware(['permission:tutor-destroy']);
    //Tutoring Package Types
    Route::get('tutoring-package-types', [TutoringPackageTypeController::class, 'index'])->name('tutoring-package-types.index')->middleware(['permission:tutoring_package_type-index']);
    Route::get('tutoring-package-types/create', [TutoringPackageTypeController::class, 'create'])->name('tutoring-package-types.create')->middleware(['permission:tutoring_package_type-create']);
    Route::post('tutoring-package-types', [TutoringPackageTypeController::class, 'store'])->name('tutoring-package-types.store')->middleware(['permission:tutoring_package_type-create']);
    Route::get('tutoring-package-types/{tutoring_package_type}', [TutoringPackageTypeController::class, 'show'])->name('tutoring-package-types.show')->middleware(['permission:tutoring_package_type-show']);
    Route::get('tutoring-package-types/{tutoring_package_type}/edit', [TutoringPackageTypeController::class, 'edit'])->name('tutoring-package-types.edit')->middleware(['permission:tutoring_package_type-edit']);
    Route::patch('tutoring-package-types/{tutoring_package_type}', [TutoringPackageTypeController::class, 'update'])->name('tutoring-package-types.update')->middleware(['permission:tutoring_package_type-edit']);
    Route::delete('tutoring-package-types/{tutoring_package_type}', [TutoringPackageTypeController::class, 'destroy'])->name('tutoring-package-types.destroy')->middleware(['permission:tutoring_package_type-destroy']);
    //Subjects
    Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index')->middleware(['permission:subject-index']);
    Route::get('subjects/create', [SubjectController::class, 'create'])->name('subjects.create')->middleware(['permission:subject-create']);
    Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store')->middleware(['permission:subject-create']);
    Route::get('subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show')->middleware(['permission:subject-show']);
    Route::get('subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit')->middleware(['permission:subject-edit']);
    Route::patch('subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update')->middleware(['permission:subject-edit']);
    Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy')->middleware(['permission:subject-destroy']);
    //Tutoring Locations
    Route::get('tutoring-locations', [TutoringLocationController::class, 'index'])->name('tutoring-locations.index')->middleware(['permission:tutoring_location-index']);
    Route::get('tutoring-locations/create', [TutoringLocationController::class, 'create'])->name('tutoring-locations.create')->middleware(['permission:tutoring_location-create']);
    Route::post('tutoring-locations', [TutoringLocationController::class, 'store'])->name('tutoring-locations.store')->middleware(['permission:tutoring_location-create']);
    Route::get('tutoring-locations/{tutoring_location}', [TutoringLocationController::class, 'show'])->name('tutoring-locations.show')->middleware(['permission:tutoring_location-show']);
    Route::get('tutoring-locations/{tutoring_location}/edit', [TutoringLocationController::class, 'edit'])->name('tutoring-locations.edit')->middleware(['permission:tutoring_location-edit']);
    Route::patch('tutoring-locations/{tutoring_location}', [TutoringLocationController::class, 'update'])->name('tutoring-locations.update')->middleware(['permission:tutoring_location-edit']);
    Route::delete('tutoring-locations/{tutoring_location}', [TutoringLocationController::class, 'destroy'])->name('tutoring-locations.destroy')->middleware(['permission:tutoring_location-destroy']);
    //Student Tutoring Packages
    Route::get('student-tutoring-packages', [StudentTutoringPackageController::class, 'index'])->name('student-tutoring-packages.index')->middleware(['permission:student_tutoring_package-index']);
    Route::get('student-tutoring-packages/create', [StudentTutoringPackageController::class, 'create'])->name('student-tutoring-packages.create')->middleware(['permission:student_tutoring_package-create']);
    Route::post('student-tutoring-packages', [StudentTutoringPackageController::class, 'store'])->name('student-tutoring-packages.store')->middleware(['permission:student_tutoring_package-create']);
    Route::get('student-tutoring-packages/{student_tutoring_package}', [StudentTutoringPackageController::class, 'show'])->name('student-tutoring-packages.show')->middleware(['permission:student_tutoring_package-show']);
    Route::get('student-tutoring-packages/{student_tutoring_package}/edit', [StudentTutoringPackageController::class, 'edit'])->name('student-tutoring-packages.edit')->middleware(['permission:student_tutoring_package-edit']);
    Route::patch('student-tutoring-packages/{student_tutoring_package}', [StudentTutoringPackageController::class, 'update'])->name('student-tutoring-packages.update')->middleware(['permission:student_tutoring_package-edit']);
    Route::delete('student-tutoring-packages/{student_tutoring_package}', [StudentTutoringPackageController::class, 'destroy'])->name('student-tutoring-packages.destroy')->middleware(['permission:student_tutoring_package-destroy']);
    Route::get('tutoring-package-type-ajax', [StudentTutoringPackageController::class, 'tutoringPackageTypeAjax'])->name('tutoring-package-type-ajax')->middleware(['permission:student_tutoring_package-create']);
    Route::get('student-email-ajax', [StudentTutoringPackageController::class, 'studentEmailAjax'])->name('student-email-ajax')->middleware(['permission:student_tutoring_package-create']);
    Route::get('tutor-email-ajax', [StudentTutoringPackageController::class, 'tutorEmailAjax'])->name('tutor-email-ajax')->middleware(['permission:student_tutoring_package-create']);
    Route::get('tutoring-location-ajax', [StudentTutoringPackageController::class, 'tutoringLocationAjax'])->name('tutoring-location-ajax')->middleware(['permission:student_tutoring_package-create']);
    ///Invoice Package Types
    Route::get('invoice-package-types', [InvoicePackageTypeController::class, 'index'])->name('invoice-package-types.index')->middleware(['permission:invoice_package_type-index']);
    Route::get('invoice-package-types/create', [InvoicePackageTypeController::class, 'create'])->name('invoice-package-types.create')->middleware(['permission:invoice_package_type-create']);
    Route::post('invoice-package-types', [InvoicePackageTypeController::class, 'store'])->name('invoice-package-types.store')->middleware(['permission:invoice_package_type-create']);
    Route::get('invoice-package-types/{invoice_package_type}', [InvoicePackageTypeController::class, 'show'])->name('invoice-package-types.show')->middleware(['permission:invoice_package_type-show']);
    Route::get('invoice-package-types/{invoice_package_type}/edit', [InvoicePackageTypeController::class, 'edit'])->name('invoice-package-types.edit')->middleware(['permission:invoice_package_type-edit']);
    Route::patch('invoice-package-types/{invoice_package_type}', [InvoicePackageTypeController::class, 'update'])->name('invoice-package-types.update')->middleware(['permission:invoice_package_type-edit']);
    Route::delete('invoice-package-types/{invoice_package_type}', [InvoicePackageTypeController::class, 'destroy'])->name('invoice-package-types.destroy')->middleware(['permission:invoice_package_type-destroy']);
    //Invoice
    Route::get('invoices',[InvoiceController::class,'index'])->name('invoices.index')->middleware(['permission:invoice-index']);
    Route::get('invoices/create',[InvoiceController::class,'create'])->name('invoices.create')->middleware(['permission:invoice-create']);
    Route::post('invoices',[InvoiceController::class,'store'])->name('invoices.store')->middleware(['permission:invoice-create']);;
    Route::get('invoices/{invoice}',[InvoiceController::class,'show'])->name('invoices.show')->middleware(['permission:invoice-show']);
    Route::get('invoices/{invoice}/edit',[InvoiceController::class,'edit'])->name('invoices.edit')->middleware(['permission:invoice-edit']);
    Route::patch('invoices/{invoice}',[InvoiceController::class,'update'])->name('invoices.update')->middleware(['permission:invoice-edit']);
    Route::delete('invoices/{invoice}',[InvoiceController::class,'destroy'])->name('invoices.destroy')->middleware(['permission:invoice-destroy']);

});

