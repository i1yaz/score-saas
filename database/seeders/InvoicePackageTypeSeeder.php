<?php

namespace Database\Seeders;

use App\Models\InvoicePackageType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoicePackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

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
