<?php

namespace Database\Seeders;

use App\Models\ListData;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ListDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ListData::insert([
            [
                'id' => 1,
                'list_id' => 1,
                'name' => 'Normal',
                'description' => 'Normal',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'list_id' => 1,
                'name' => 'Partial',
                'description' => 'Partial',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'list_id' => 1,
                'name' => 'Missed (Will charge for missed time)',
                'description' => 'Missed (Will charge for missed time)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'list_id' => 1,
                'name' => 'Canceled (Will not be  charged)',
                'description' => 'Canceled (Will not be  charged)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'list_id' => 1,
                'name' => 'Void',
                'description' => 'Void',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'list_id' => 2,
                'name' => 'SAT',
                'description' => 'SAT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'list_id' => 2,
                'name' => 'ACT',
                'description' => 'ACT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'list_id' => 2,
                'name' => 'ISEE',
                'description' => 'ISEE',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'list_id' => 2,
                'name' => 'SSAT',
                'description' => 'SSAT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
