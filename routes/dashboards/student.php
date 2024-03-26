<?php

use App\Http\Controllers\Dashboards\StudentDashboardController;

Route::group(['middleware' => ['auth:web,student','redirect.url']], function () {
    Route::get('student/dashboard', [StudentDashboardController::class, 'index'])->name('student-dashboard.index')->middleware('permission:student-dashboard');
});
