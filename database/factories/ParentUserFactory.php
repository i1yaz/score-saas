<?php

namespace Database\Factories;

use App\Models\ParentUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentUserFactory extends Factory
{
    protected $model = ParentUser::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('abcd1234'),
            'email_verified_at' => Carbon::now(),
            'remember_token' => Str::random(10),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'address2' => $this->faker->address(),
            'phone_alternate' => $this->faker->phoneNumber(),
            'referral_source' => $this->faker->word(),
            'added_by' => 1,
            'referral_from_positive_experience_with_tutor' => $this->faker->boolean(),
            'auth_guard' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
