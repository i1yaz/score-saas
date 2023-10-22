<?php

namespace Database\Factories;

use App\Models\MonthlyInvoicePackage;
use App\Models\Session;
use App\Models\StudentTutoringPackage;
use App\Models\Tutor;
use App\Models\TutoringLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        return [
            'student_tutoring_package_id' => StudentTutoringPackage::factory(),
            'tutor_id' => Tutor::factory(),
            'tutoring_location_id' => TutoringLocation::factory(),
            'scheduled_date' => Carbon::yesterday()->toDateString(),
            'start_time' => Carbon::yesterday()->setTime(14,0,0),
            'end_time' => Carbon::yesterday()->setTime(15,0,0),
            'pre_session_notes' => $this->faker->word(),
            'session_completion_code' => $this->faker->randomNumber(),
            'attended_duration' => null,
            'charge_for_missed_session' => 0,
            'attended_start_time' => null,
            'attended_end_time' => null,
            'charge_missed_time' => 0,
            'charged_missed_session' => null,
            'how_was_session' => $this->faker->randomNumber(),
            'student_parent_session_notes' => $this->faker->word(),
            'homework' => $this->faker->word(),
            'internal_notes' => $this->faker->word(),
            'flag_session' => $this->faker->boolean(),
            'home_work_completed' => $this->faker->boolean(),
            'auth_guard' => 'web',
            'added_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

