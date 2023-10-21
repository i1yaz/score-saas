<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Models\TutoringLocation;
use App\Models\TutoringPackageType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentTutoringPackageFactory extends Factory
{
    protected $model = StudentTutoringPackage::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'tutoring_package_type_id' => TutoringPackageType::factory(),
            'tutoring_location_id' => TutoringLocation::factory(),
            'notes' => $this->faker->paragraph,
            'internal_notes' => $this->faker->paragraph,
            'hours' => $this->faker->numberBetween(24,72),
            'hourly_rate' => $this->faker->numberBetween(80,200),
            'discount' => $this->faker->numberBetween(1,40),
            'discount_type' => $this->faker->randomElement([StudentTutoringPackage::FLAT_DISCOUNT,StudentTutoringPackage::PERCENTAGE_DISCOUNT]),
            'start_date' => Carbon::yesterday()->setTime(14,0),
            'tutor_hourly_rate' => $this->faker->numberBetween(20,50),
            'auth_guard' => 'web',
            'added_by' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
