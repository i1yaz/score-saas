<?php

namespace Database\Seeders;

use App\Models\ListData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ListData::create([
            'list_id' => 1,
            'name' => 'Normal',
            'description' => 'Normal',
        ]);
        ListData::create([
            'list_id' => 1,
            'name' => 'Partial',
            'description' => 'Partial',
        ]);
        ListData::create([
            'list_id' => 1,
            'name' => 'Missed (Will charge for missed time)',
            'description' => 'Missed (Will charge for missed time)',
        ]);
        ListData::create([
            'list_id' => 1,
            'name' => 'Canceled (Will not be  charged)',
            'description' => 'Canceled (Will not be  charged)',
        ]);
        ListData::create([
            'list_id' => 1,
            'name' => 'Void',
            'description' => 'Void',
        ]);
    }
}
