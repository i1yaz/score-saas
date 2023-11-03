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

        InvoicePackageType::insert([
            [
                'id' => 1,
                'name' => 'Tutoring Package',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ],
            [
                'id' => 2,
                'name' => 'Monthly Invoice Tutoring',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ],
            [
                'id' => 3,
                'name' => 'Non Package Invoice',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'auth_guard' => 'web',
                'added_by' => 1
            ]
        ]);
    }
}
