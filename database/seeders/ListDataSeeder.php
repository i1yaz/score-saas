<?php

namespace Database\Seeders;

use App\Models\ListData;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'list_id' => 1,
                'name' => 'Normal',
                'description' => 'Normal',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'list_id' => 1,
                'name' => 'Partial',
                'description' => 'Partial',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'list_id' => 1,
                'name' => 'Missed (Will charge for missed time)',
                'description' => 'Missed (Will charge for missed time)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'list_id' => 1,
                'name' => 'Canceled (Will not be  charged)',
                'description' => 'Canceled (Will not be  charged)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'list_id' => 1,
                'name' => 'Void',
                'description' => 'Void',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
