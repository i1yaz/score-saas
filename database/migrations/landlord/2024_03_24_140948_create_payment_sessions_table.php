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
        Schema::create('payment_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('creator_fullname', 150)->nullable();
            $table->string('creator_email', 150)->nullable();
            $table->string('gateway_name', 150)->nullable()->comment('stripe | paypal | etc');
            $table->string('gateway_ref', 150)->nullable()->comment('the checkout_id (either from the gateway or system generated)');
            $table->string('gateway_ref_2', 150)->nullable();
            $table->decimal('amount', 10, 2)->nullable()->comment('amount of the payment');
            $table->string('invoices', 250)->nullable()->comment('[currently] - single invoice id | [future] - comma seperated list of invoice id\'s that are for this payment');
            $table->integer('subscription_id')->nullable()->comment('subscription id');
            $table->text('payload');
            $table->integer('added_by')->nullable()->comment('user making the payment');
            $table->string('status', 20)->default('pending')->comment('pending|completed');
            $table->timestamps();
            $table->index('gateway_name');
            $table->index('gateway_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_sessions');
    }
};
