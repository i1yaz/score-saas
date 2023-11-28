<?php

namespace Database\Factories;

use App\Models\ParentUser;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'parent_id' => ParentUser::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('abcd1234'),
            'remember_token' => Str::random(10),
            'email_known' => $this->faker->boolean,
            'testing_accommodation' => $this->faker->boolean,
            'testing_accommodation_nature' => $this->faker->word(),
            'official_baseline_act_score' => $this->faker->numberBetween(100, 500),
            'official_baseline_sat_score' => $this->faker->numberBetween(100, 500),
            'test_anxiety_challenge' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => $this->faker->boolean(),
            'auth_guard' => 'web',
            'added_by' => 1,
        ];
    }
}
