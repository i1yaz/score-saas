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
//Route::put('parents/{parent}',[App\Http\Controllers\ParentController::class,'update'])->name('parents.update')->middleware(['permission:parent-edit']);
//Route::delete('parents/{parent}',[App\Http\Controllers\ParentController::class,'destroy'])->name('parents.destroy')->middleware(['permission:parent-destroy']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();


Route::group(['middleware' => 'auth'],function (){
    Route::get('parents',[App\Http\Controllers\ParentController::class,'index'])->name('parents.index')->middleware(['permission:parent-index']);
    Route::get('parents/create',[App\Http\Controllers\ParentController::class,'create'])->name('parents.create')->middleware(['permission:parent-create']);
    Route::post('parents',[App\Http\Controllers\ParentController::class,'store'])->name('parents.store')->middleware(['permission:parent-create']);;
    Route::get('parents/{parent}',[App\Http\Controllers\ParentController::class,'show'])->name('parents.show')->middleware(['permission:parent-show']);
    Route::get('parents/{parent}/edit',[App\Http\Controllers\ParentController::class,'edit'])->name('parents.edit')->middleware(['permission:parent-edit']);
    Route::put('parents/{parent}',[App\Http\Controllers\ParentController::class,'update'])->name('parents.update')->middleware(['permission:parent-edit']);
    Route::delete('parents/{parent}',[App\Http\Controllers\ParentController::class,'destroy'])->name('parents.destroy')->middleware(['permission:parent-destroy']);
});


