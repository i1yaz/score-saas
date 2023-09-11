<?php

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
//Route::get('school',[App\Http\Controllers\SchoolController::class,'index'])->name('schools.index')->middleware(['permission:school-index']);
//Route::get('school/create',[App\Http\Controllers\SchoolController::class,'create'])->name('schools.create')->middleware(['permission:school-create']);
//Route::post('school',[App\Http\Controllers\SchoolController::class,'store'])->name('schools.store')->middleware(['permission:school-create']);;
//Route::get('school/{school}',[App\Http\Controllers\SchoolController::class,'show'])->name('schools.show')->middleware(['permission:school-show']);
//Route::get('school/{school}/edit',[App\Http\Controllers\SchoolController::class,'edit'])->name('schools.edit')->middleware(['permission:school-edit']);
//Route::patch('school/{school}',[App\Http\Controllers\SchoolController::class,'update'])->name('schools.update')->middleware(['permission:school-edit']);
//Route::delete('school/{school}',[App\Http\Controllers\SchoolController::class,'destroy'])->name('schools.destroy')->middleware(['permission:school-destroy']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();


Route::group(['middleware' => 'auth'],function (){
    //ACL
    Route::get('acl/permissions',[\App\Http\Controllers\ACL\PermissionsController::class,'index'])->name('acl.permissions.index');
    Route::get('acl/permissions/create',[\App\Http\Controllers\ACL\PermissionsController::class,'create'])->name('acl.permissions.create');
    Route::post('acl/permissions',[\App\Http\Controllers\ACL\PermissionsController::class,'store'])->name('acl.permission.store');
    Route::get('acl/permissions/{permission}/edit',[\App\Http\Controllers\ACL\PermissionsController::class,'edit'])->name('acl.permissions.edit');
    Route::patch('acl/permissions/{permission}',[\App\Http\Controllers\ACL\PermissionsController::class,'update'])->name('acl.permissions.update');

    Route::get('acl/roles',[\App\Http\Controllers\ACL\RolesController::class,'index'])->name('acl.roles.index');
    Route::get('acl/roles/create',[\App\Http\Controllers\ACL\RolesController::class,'create'])->name('acl.roles.create');
    Route::get('acl/{role}',[\App\Http\Controllers\ACL\RolesController::class,'show'])->name('acl.roles.show');
    Route::post('acl/roles',[\App\Http\Controllers\ACL\RolesController::class,'store'])->name('acl.roles.store');
    Route::get('acl/roles/{role}/edit',[\App\Http\Controllers\ACL\RolesController::class,'edit'])->name('acl.roles.edit');
    Route::patch('acl/roles/{role}',[\App\Http\Controllers\ACL\RolesController::class,'update'])->name('acl.roles.update');
    Route::delete('acl/roles/{role}',[\App\Http\Controllers\ACL\RolesController::class,'destroy'])->name('acl.roles.destroy');
    //Parent
    Route::get('parents',[App\Http\Controllers\ParentController::class,'index'])->name('parents.index')->middleware(['permission:parent-index']);
    Route::get('parents/create',[App\Http\Controllers\ParentController::class,'create'])->name('parents.create')->middleware(['permission:parent-create']);
    Route::post('parents',[App\Http\Controllers\ParentController::class,'store'])->name('parents.store')->middleware(['permission:parent-create']);;
    Route::get('parents/{parent}',[App\Http\Controllers\ParentController::class,'show'])->name('parents.show')->middleware(['permission:parent-show']);
    Route::get('parents/{parent}/edit',[App\Http\Controllers\ParentController::class,'edit'])->name('parents.edit')->middleware(['permission:parent-edit']);
    Route::patch('parents/{parent}',[App\Http\Controllers\ParentController::class,'update'])->name('parents.update')->middleware(['permission:parent-edit']);
    Route::delete('parents/{parent}',[App\Http\Controllers\ParentController::class,'destroy'])->name('parents.destroy')->middleware(['permission:parent-destroy']);
    //Student
    Route::get('students',[App\Http\Controllers\StudentController::class,'index'])->name('students.index')->middleware(['permission:student-index']);
    Route::get('students/create',[App\Http\Controllers\StudentController::class,'create'])->name('students.create')->middleware(['permission:student-create']);
    Route::post('students',[App\Http\Controllers\StudentController::class,'store'])->name('students.store')->middleware(['permission:student-create']);;
    Route::get('students/{parent}',[App\Http\Controllers\StudentController::class,'show'])->name('students.show')->middleware(['permission:student-show']);
    Route::get('students/{parent}/edit',[App\Http\Controllers\StudentController::class,'edit'])->name('students.edit')->middleware(['permission:student-edit']);
    Route::patch('students/{parent}',[App\Http\Controllers\StudentController::class,'update'])->name('students.update')->middleware(['permission:student-edit']);
    Route::delete('students/{parent}',[App\Http\Controllers\StudentController::class,'destroy'])->name('students.destroy')->middleware(['permission:student-destroy']);
    Route::get('student-parent-ajax', [App\Http\Controllers\StudentController::class,'studentParentAjax'])->name('student-parent-ajax')->middleware(['permission:student-create']);
    Route::get('student-school-ajax', [App\Http\Controllers\StudentController::class,'studentSchoolAjax'])->name('student-school-ajax')->middleware(['permission:student-create']);
    //School
    Route::get('schools',[App\Http\Controllers\SchoolController::class,'index'])->name('schools.index')->middleware(['permission:school-index']);
    Route::get('schools/create',[App\Http\Controllers\SchoolController::class,'create'])->name('schools.create')->middleware(['permission:school-create']);
    Route::post('schools',[App\Http\Controllers\SchoolController::class,'store'])->name('schools.store')->middleware(['permission:school-create']);;
    Route::get('schools/{school}',[App\Http\Controllers\SchoolController::class,'show'])->name('schools.show')->middleware(['permission:school-show']);
    Route::get('schools/{school}/edit',[App\Http\Controllers\SchoolController::class,'edit'])->name('schools.edit')->middleware(['permission:school-edit']);
    Route::patch('schools/{school}',[App\Http\Controllers\SchoolController::class,'update'])->name('schools.update')->middleware(['permission:school-edit']);
    Route::delete('schools/{school}',[App\Http\Controllers\SchoolController::class,'destroy'])->name('schools.destroy')->middleware(['permission:school-destroy']);

});

