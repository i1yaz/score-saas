<?php

namespace Database\Factories;

use App\Models\MockTestCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MockTestCodeFactory extends Factory
{
    protected $model = MockTestCode::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
        ];
    }
}
