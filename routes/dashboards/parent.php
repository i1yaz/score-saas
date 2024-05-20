<?php

use App\Http\Controllers\Dashboards\ParentDashboardController;

Route::group(['middleware' => ['auth:web,parent']], function () {
    Route::get('parent/dashboard', [ParentDashboardController::class, 'index'])->name('parent-dashboard.index')->middleware('permission:parent-dashboard');
});
