<?php

use App\Http\Controllers\ACL\PermissionsController;
use App\Http\Controllers\ACL\RolesAssignmentController;
use App\Http\Controllers\ACL\RolesController;

Route::group(['middleware' => ['auth', 'acl.access']], function () {
    //Permissions
    Route::get('acl/permissions', [PermissionsController::class, 'index'])->name('acl.permissions.index');
    Route::get('acl/permissions/create', [PermissionsController::class, 'create'])->name('acl.permissions.create');
    Route::post('acl/permissions', [PermissionsController::class, 'store'])->name('acl.permission.store');
    Route::get('acl/permissions/{permission}/edit', [PermissionsController::class, 'edit'])->name('acl.permissions.edit');
    Route::patch('acl/permissions/{permission}', [PermissionsController::class, 'update'])->name('acl.permissions.update');
    //Roles
    Route::get('acl/roles', [RolesController::class, 'index'])->name('acl.roles.index');
    Route::get('acl/roles/create', [RolesController::class, 'create'])->name('acl.roles.create');
    Route::get('acl/roles/{role}', [RolesController::class, 'show'])->name('acl.roles.show');
    Route::post('acl/roles', [RolesController::class, 'store'])->name('acl.roles.store');
    Route::get('acl/roles/{role}/edit', [RolesController::class, 'edit'])->name('acl.roles.edit');
    Route::patch('acl/roles/{role}', [RolesController::class, 'update'])->name('acl.roles.update');
    Route::delete('acl/roles/{role}', [RolesController::class, 'destroy'])->name('acl.roles.destroy');
    //Role Assignment
    Route::get('acl/assignments', [RolesAssignmentController::class, 'index'])->name('acl.assignments.index');
    Route::get('acl/assignments/{roles_assignment}/edit', [RolesAssignmentController::class, 'edit'])->name('acl.assignments.edit');
    Route::patch('acl/assignments/{roles_assignment}', [RolesAssignmentController::class, 'update'])->name('acl.assignments.update');
});
