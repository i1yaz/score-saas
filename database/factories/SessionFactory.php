<?php

namespace Database\Factories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        return [
            'student_tutoring_package_id' => $this->faker->randomNumber(),
            'scheduled_date' => Carbon::now(),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now(),
            'location_id' => $this->faker->randomNumber(),
            'pre_session_notes' => $this->faker->word(),
            'session_completion_code' => $this->faker->randomNumber(),
            'how_was_session' => $this->faker->randomNumber(),
            'student_parent_session_notes' => $this->faker->word(),
            'homework' => $this->faker->word(),
            'internal_notes' => $this->faker->word(),
            'flag_session' => $this->faker->boolean(),
            'home_work_completed' => $this->faker->boolean(),
            'practice_test_for_homework' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
