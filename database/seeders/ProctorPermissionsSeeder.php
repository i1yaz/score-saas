<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProctorPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::whereIn('name', [
            'proctor-index',
            'proctor-edit',
            'proctor-show',
            'mock_test-index',
            'mock_test-show',
            'mock_test-score',
            'proctor_dashboard-index',
            'mock_test-student',

        ])->get();
        $tutor = Role::where('name', 'proctor')->first();
        $tutor->givePermissions($permissions);
    }
}
