<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutorPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::whereIn('name',[
            'parent-index',
            'parent-show',
            'student-index',
            'student-show',
            'tutor-index',
            'tutor-edit',
            'tutor-show',
            'tutor_dashboard-index',
            'session-index',
            'session-create',
            'session-show',
            'session-edit'
        ])->get();
        $tutor = Role::where('name', 'tutor')->first();
        $tutor->givePermissions($permissions);
    }
}
