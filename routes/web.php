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
//Route::get('parents',[App\Http\Controllers\ParentController::class,'index'])->name('parents.index')->middleware(['permission:parent-index']);
//Route::get('parents/create',[App\Http\Controllers\ParentController::class,'create'])->name('parents.create')->middleware(['permission:parent-create']);
//Route::post('parents',[App\Http\Controllers\ParentController::class,'store'])->name('parents.store')->middleware(['permission:parent-create']);;
//Route::get('parents/{parent}',[App\Http\Controllers\ParentController::class,'show'])->name('parents.show')->middleware(['permission:parent-show']);
//Route::get('parents/{parent}/edit',[App\Http\Controllers\ParentController::class,'edit'])->name('parents.edit')->middleware(['permission:parent-edit']);
//Route::patch('parents/{parent}',[App\Http\Controllers\ParentController::class,'update'])->name('parents.update')->middleware(['permission:parent-edit']);
//Route::delete('parents/{parent}',[App\Http\Controllers\ParentController::class,'destroy'])->name('parents.destroy')->middleware(['permission:parent-destroy']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();


Route::group(['middleware' => 'auth'],function (){
    //Parent
    Route::get('parents',[App\Http\Controllers\ParentController::class,'index'])->name('parents.index')->middleware(['permission:parent-index']);
    Route::get('parents/create',[App\Http\Controllers\ParentController::class,'create'])->name('parents.create')->middleware(['permission:parent-create']);
    Route::post('parents',[App\Http\Controllers\ParentController::class,'store'])->name('parents.store')->middleware(['permission:parent-create']);;
    Route::get('parents/{parent}',[App\Http\Controllers\ParentController::class,'show'])->name('parents.show')->middleware(['permission:parent-show']);
    Route::get('parents/{parent}/edit',[App\Http\Controllers\ParentController::class,'edit'])->name('parents.edit')->middleware(['permission:parent-edit']);
    Route::patch('parents/{parent}',[App\Http\Controllers\ParentController::class,'update'])->name('parents.update')->middleware(['permission:parent-edit']);
    Route::delete('parents/{parent}',[App\Http\Controllers\ParentController::class,'destroy'])->name('parents.destroy')->middleware(['permission:parent-destroy']);
    //Student
    Route::get('student',[App\Http\Controllers\StudentController::class,'index'])->name('student.index')->middleware(['permission:student-index']);
    Route::get('student/create',[App\Http\Controllers\StudentController::class,'create'])->name('student.create')->middleware(['permission:student-create']);
    Route::post('student',[App\Http\Controllers\StudentController::class,'store'])->name('student.store')->middleware(['permission:student-create']);;
    Route::get('student/{parent}',[App\Http\Controllers\StudentController::class,'show'])->name('student.show')->middleware(['permission:student-show']);
    Route::get('student/{parent}/edit',[App\Http\Controllers\StudentController::class,'edit'])->name('student.edit')->middleware(['permission:student-edit']);
    Route::patch('student/{parent}',[App\Http\Controllers\StudentController::class,'update'])->name('student.update')->middleware(['permission:student-edit']);
    Route::delete('student/{parent}',[App\Http\Controllers\StudentController::class,'destroy'])->name('student.destroy')->middleware(['permission:student-destroy']);
    Route::get('student-parent-ajax', [App\Http\Controllers\StudentController::class,'studentParentAjax'])->name('student-parent-ajax')->middleware(['permission:student-create']);
});



Route::resource('students', App\Http\Controllers\StudentController::class);
