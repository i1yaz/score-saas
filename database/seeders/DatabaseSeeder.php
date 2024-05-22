<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
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

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            InvoicePackageTypeSeeder::class,
            ListDataSeeder::class,
            InvoicePackageSeeder::class,
            MailTemplatesSeeder::class,
            ClientPermissionsSeeder::class,
            ParentPermissionsSeeder::class,
            StudentPermissionsSeeder::class,
            InvoicePaymentReminderTemplateSeeder::class,
            TutorPermissionsSeeder::class,

        ]);

        $user = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('abcd1234'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $user->addRole('super-admin');
    }
}
