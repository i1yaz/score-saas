<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\InvoicePackageType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('abcd1234'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        InvoicePackageType::create(
            [
                'name' => 'Tutoring Package',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ]);
        InvoicePackageType::create(
            [
                'name' => 'Monthly Invoice Tutoring',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ]);
        InvoicePackageType::create(
            [
                'name' => 'Mock Test',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ]);
        InvoicePackageType::create(
            [
                'name' => 'At Home Sessions',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ]);
        InvoicePackageType::create(
            [
                'name' => 'Other',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ]);
    }
}
