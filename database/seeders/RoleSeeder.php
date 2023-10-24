<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Insert data into the roles table
        Role::insert([
            [
                'name' => 'super-admin',
                'display_name' => 'Super Admin',
                'description' => 'Super man',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Role of Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Role of Parent',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Role of student',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'tutor',
                'display_name' => 'Tutor',
                'description' => 'Role of tutor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'proctor',
                'display_name' => 'Proctor',
                'description' => 'Role of Proctor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'client',
                'display_name' => 'Client',
                'description' => 'Role of client',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'developer',
                'display_name' => 'Developer',
                'description' => 'Developers',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

    }
}
