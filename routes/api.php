<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('packages',[App\Http\Controllers\SchoolController::class,'index'])->name('packages.index')->middleware(['permission:package-index']);
Route::get('packages/create',[App\Http\Controllers\SchoolController::class,'create'])->name('packages.create')->middleware(['permission:package-create']);
Route::post('packages',[App\Http\Controllers\SchoolController::class,'store'])->name('packages.store')->middleware(['permission:package-create']);;
Route::get('packages/{package}',[App\Http\Controllers\SchoolController::class,'show'])->name('packages.show')->middleware(['permission:package-show']);
Route::get('packages/{package}/edit',[App\Http\Controllers\SchoolController::class,'edit'])->name('packages.edit')->middleware(['permission:package-edit']);
Route::patch('packages/{package}',[App\Http\Controllers\SchoolController::class,'update'])->name('packages.update')->middleware(['permission:package-edit']);
Route::delete('packages/{package}',[App\Http\Controllers\SchoolController::class,'destroy'])->name('packages.destroy')->middleware(['permission:package-destroy']);
