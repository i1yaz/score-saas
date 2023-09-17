<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'school_id' => $this->faker->randomNumber(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email_known' => $this->faker->unique()->safeEmail(),
            'testing_accommodation' => $this->faker->boolean(),
            'testing_accommodation_nature' => $this->faker->word(),
            'official_baseline_act_score' => $this->faker->word(),
            'official_baseline_sat_score' => $this->faker->word(),
            'test_anxiety_challenge' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'parent_id' => $this->faker->randomNumber(),
            'status' => $this->faker->boolean(),
            'added_by' => $this->faker->randomNumber(),
            'added_at' => Carbon::now(),
            'user_id' => User::factory(),
        ];
    }
}
