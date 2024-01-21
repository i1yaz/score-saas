<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ParentPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::whereIn('name', [
            'parent-index',
            'parent-edit',
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
            'parent-dashboard',
        ])->get();

        $parent = Role::where('name', 'parent')->first();
        $parent->givePermissions($permissions);
    }
}
