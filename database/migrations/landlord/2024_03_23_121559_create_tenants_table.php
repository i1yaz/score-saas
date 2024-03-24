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
        Schema::connection('landlord')->create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('domain_type')->default('subdomain')->comment('subdomain|custom');
            $table->string('database')->nullable();
            $table->integer('creator_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('stripe_customer_id')->nullable()->comment('used for stripe checkout');
            $table->string('paystack_customer_id')->nullable()->comment('used for paystack checkout');
            $table->string('razorpay_customer_id')->nullable()->comment('used for razorpay checkout');
            $table->string('status')->default('unsubscribed')->comment('unsubscribed|free-trial|awaiting-payment|failed|active|cancelled');
            $table->string('password')->nullable();
            $table->date('last_synced')->nullable();
            $table->string('email_config_type')->default('local')->comment('local|smtp');
            $table->string('email_config_status')->default('pending');
            $table->string('email_local_email')->nullable();
            $table->string('email_forwarding_email')->nullable();
            $table->string('sync_status')->nullable()->comment('awaiting-sync|null');
            $table->string('sync_user')->nullable()->comment('awaiting-sync|null');
            $table->string('updating_current_version')->nullable();
            $table->string('updating_target_version')->nullable();
            $table->string('updating_status')->default('completed')->comment('completed|failed');
            $table->text('updating_log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
