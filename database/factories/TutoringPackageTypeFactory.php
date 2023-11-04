<?php

namespace Database\Factories;

use App\Models\TutoringPackageType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TutoringPackageType>
 */
class TutoringPackageTypeFactory extends Factory
{
    protected $model = TutoringPackageType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'hours' => $this->faker->numberBetween(24, 72),
            'auth_guard' => 'web',
            'added_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
