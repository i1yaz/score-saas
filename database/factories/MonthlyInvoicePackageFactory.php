<?php

namespace Database\Factories;

use App\Models\MonthlyInvoicePackage;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Models\TutoringLocation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthlyInvoicePackageFactory extends Factory
{
    protected $model = MonthlyInvoicePackage::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'tutoring_location_id' => TutoringLocation::factory(),
            'notes' => $this->faker->paragraph,
            'internal_notes' => $this->faker->paragraph,
            'hourly_rate' => $this->faker->numberBetween(80, 200),
            'discount' => $this->faker->numberBetween(1, 40),
            'discount_type' => $this->faker->randomElement([StudentTutoringPackage::FLAT_DISCOUNT, StudentTutoringPackage::PERCENTAGE_DISCOUNT]),
            'start_date' => Carbon::yesterday()->setTime(14, 0),
            'tutor_hourly_rate' => $this->faker->numberBetween(20, 50),
            'is_free' => $this->faker->boolean,
            'is_score_guaranteed' => $this->faker->boolean,
            'auth_guard' => 'web',
            'added_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
