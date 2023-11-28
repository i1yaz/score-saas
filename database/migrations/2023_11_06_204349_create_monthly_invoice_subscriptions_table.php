<?php

use App\Models\ListData;
use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_invoice_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MonthlyInvoicePackage::class)->constrained();
            $table->string('payment_gateway');
            $table->string('subscription_id');
            $table->string('subscription_status')->default(MonthlyInvoiceSubscription::STATUS_ACTIVE);
            $table->string('frequency')->default(MonthlyInvoiceSubscription::MONTHLY_INVOICE_FREQUENCY);
            $table->string('start_date');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_invoice_subscriptions');
    }
};
