<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class StudentPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::whereIn('name', [
            'parent-index',
            'parent-show',
            'student-index',
            'student-edit',
            'student-show',
            'tutor-index',
            'tutor-show',
            'invoice-index',
            'invoice-show',
            'invoice-pay',
            'session-index',
            'session-show',
            'payment-index',
            'payment-show',
            'student-dashboard'
        ])->get();

        $student = Role::where('name', 'student')->first();
        $student->givePermissions($permissions);
    }
}
