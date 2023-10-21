<?php

namespace Database\Factories;

use App\Models\TutoringLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TutoringLocationFactory extends Factory
{
    protected $model = TutoringLocation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'auth_guard' => 'web',
            'added_by' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
