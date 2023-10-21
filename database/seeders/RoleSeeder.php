<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Insert data into the roles table
        Role::create([
            'name' => 'super-admin',
            'display_name' => 'Super Admin',
            'description' => 'Super man',
        ]);

        Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Role of Admin',
        ]);

        Role::create([
            'name' => 'parent',
            'display_name' => 'Parent',
            'description' => 'Role of Parent',
        ]);

        Role::create([
            'name' => 'student',
            'display_name' => 'Student',
            'description' => 'Role of student',
        ]);

        Role::create([
            'name' => 'tutor',
            'display_name' => 'Tutor',
            'description' => 'Role of tutor',
        ]);

        Role::create([
            'name' => 'proctor',
            'display_name' => 'Proctor',
            'description' => 'Role of Proctor',
        ]);

        Role::create([
            'name' => 'client',
            'display_name' => 'Client',
            'description' => 'Role of client',
        ]);

        Role::create([
            'name' => 'developer',
            'display_name' => 'Developer',
            'description' => 'Developers',
        ]);
    }
}
