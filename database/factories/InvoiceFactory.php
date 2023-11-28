<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoicePackageType;
use App\Models\ParentUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'invoice_package_type_id' => InvoicePackageType::factory(),
            'due_date' => Carbon::now()->addWeek(),
            'fully_paid_at' => Carbon::now(),
            'general_description' => $this->faker->text(),
            'detailed_description' => $this->faker->text(),
            'email_to_parent' => $this->faker->unique()->safeEmail(),
            'email_to_student' => $this->faker->unique()->safeEmail(),
            'amount_paid' => $this->faker->randomFloat(),
            'paid_status' => $this->faker->randomElement([Invoice::DRAFT, Invoice::PENDING, Invoice::PARTIAL_PAYMENT, Invoice::PAID, Invoice::VOID]),
            'paid_by_modal' => ParentUser::class,
            'paid_by_id' => $this->faker->randomNumber(),
            'invoiceable_type' => $this->faker->word(),
            'invoiceable_id' => $this->faker->randomNumber(),
            'status' => $this->faker->boolean(),
            'auth_guard' => 'web',
            'added_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
