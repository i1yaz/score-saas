<?php

namespace Database\Seeders;

use App\Models\TutoringLocation;
use App\Models\TutoringPackageType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoicePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TutoringPackageType::insert([
            [
                'id' => 1,
                'name' => '24 Hours Tutoring Package',
                'hours' => 24,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ],
            [
                'id' => 2,
                'name' => '48 Hours Tutoring Package',
                'hours' => 48,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ],
        ]);
        TutoringLocation::insert([
            [
                'name' => 'Online',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ],
            [
                'name' => 'Home',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ],
            [
                'name' => 'Zoom',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ]
        ]);
    }
}
