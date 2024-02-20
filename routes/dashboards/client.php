<?php

use App\Http\Controllers\Dashboards\ClientDashboardController;

Route::group(['middleware' => ['auth:web,client']], function () {
    Route::get('client/dashboard', [ClientDashboardController::class, 'index'])->name('client-dashboard.index')->middleware('permission:client-dashboard');
});
