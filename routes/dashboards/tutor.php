<?php

use App\Http\Controllers\Dashboards\TutorDashboardController;

Route::group(['middleware' => ['auth:web,tutor']], function () {
    Route::get('tutor/dashboard', [TutorDashboardController::class, 'index'])->name('tutor-dashboard.index')->middleware('permission:tutor_dashboard-index');
    Route::get('tutor/calendar', [TutorDashboardController::class, 'calendar'])->name('tutor-dashboard.calendar')->middleware('permission:tutor_dashboard-calendar');
});
