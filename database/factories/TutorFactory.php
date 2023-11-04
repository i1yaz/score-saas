<?php

namespace Database\Factories;

use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TutorFactory extends Factory
{
    protected $model = Tutor::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('abcd1234'),
            'remember_token' => Str::random(10),
            'secondary_email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'secondary_phone' => $this->faker->phoneNumber,
            'picture' => $this->faker->imageUrl(),
            'start_date' => $this->faker->dateTimeThisMonth,
            'status' => $this->faker->boolean,
            'auth_guard' => 'web',
            'added_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'hourly_rate' => $this->faker->numberBetween(20, 80),

        ];
    }
}
