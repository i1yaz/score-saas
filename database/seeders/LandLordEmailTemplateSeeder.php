<?php

namespace Database\Seeders;

use App\Models\Landlord\EmailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LandLordEmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sql = File::get(database_path('email_templates.sql'));
        DB::connection('landlord')->unprepared($sql);
    }
}
