<?php

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
        Schema::connection('landlord')->create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_id', 100)->nullable()->comment('from gateway');
            $table->string('status', 100)->default('awaiting-payment')->comment('free-trial | awaiting-payment | active | failed | cancelled');
            $table->integer('customer_id')->nullable();
            $table->string('unique_id', 200)->nullable();
            $table->integer('added_by')->nullable();
            $table->string('type', 30)->default('paid')->comment('paid|free');
            $table->string('payment_method', 50)->default('automatic')->comment('automatic|offline');
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('trial_end')->nullable();
            $table->date('date_started')->nullable();
            $table->date('date_renewed')->nullable()->comment('from gateway');
            $table->date('date_next_renewal')->nullable()->comment('from gateway');
            $table->integer('package_id')->nullable()->comment('the planid, when subscription was created');
            $table->string('gateway_name', 50)->nullable()->comment('stripe|paypal');
            $table->string('gateway_plan_id', 100)->nullable()->comment('[stripe = product] [paypal = foo]');
            $table->string('gateway_price_id', 100)->nullable()->comment('[stripe = price] [paypal = null]');
            $table->string('gateway_billing_cycle', 100)->nullable()->comment('monthly|yearly');
            $table->text('gateway_last_message')->nullable()->comment('(optional) from gateway');
            $table->date('gateway_last_date')->nullable()->comment('(optional) from gateway');
            $table->string('checkout_reference', 200)->nullable()->comment('(optional) any additional data');
            $table->string('checkout_reference_2', 200)->nullable()->comment('(optional) any additional data');
            $table->string('checkout_reference_3', 200)->nullable()->comment('(optional) any additional data');
            $table->string('checkout_reference_4', 200)->nullable()->comment('(optional) any additional data');
            $table->string('checkout_reference_5', 200)->nullable()->comment('(optional) any additional data');
            $table->text('checkout_payload')->nullable()->comment('(optional) any additional data');
            $table->string('archived', 20)->default('no')->comment('yes|no');
            $table->timestamps();

            $table->index('added_by');
            $table->index('customer_id');
            $table->index('status');
            $table->index('type');
            $table->index('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
