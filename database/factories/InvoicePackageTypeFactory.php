<?php

namespace Database\Factories;

use App\Models\InvoicePackageType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoicePackageTypeFactory extends Factory
{
    protected $model = InvoicePackageType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'auth_guard' => 'web',
            'added_by' => 1,
        ];
    }
}
