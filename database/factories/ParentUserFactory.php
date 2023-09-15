<?php

namespace Database\Factories;

use App\Models\ParentUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ParentUserFactory extends Factory
{
    protected $model = ParentUser::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'secondary_email' => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->boolean(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'address2' => $this->faker->address(),
            'phone_alternate' => $this->faker->phoneNumber(),
            'referral_source' => $this->faker->word(),
            'added_by' => $this->faker->randomNumber(),
            'added_on' => Carbon::now(),
            'referral_from_positive_experience_with_tutor' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id' => User::factory(),
        ];
    }
}
