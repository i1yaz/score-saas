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

//  KEY `package_creatorid` (`package_creatorid`),
//  KEY `package_featured` (`package_featured`),
//  KEY `package_status` (`package_status`),
//  KEY `package_visibility` (`package_visibility`)
//) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='[truncate]';
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->nullable();
            $table->string('subscription_options', 150)->default('paid')->comment('free|paid');
            $table->decimal('amount_monthly', 10, 2)->default('0.00');
            $table->decimal('amount_yearly', 10, 2)->default('0.00');
            $table->string('gateway_stripe_product_monthly', 150)->nullable()->comment('API Info - Product is the parent to a plan');
            $table->string('gateway_stripe_price_monthly', 150)->nullable()->comment('API Info - This is called "price:');
            $table->string('gateway_stripe_product_yearly', 150)->nullable()->comment('API Info - Product is the parent to a plan');
            $table->string('gateway_stripe_price_yearly', 150)->nullable()->comment('API Info - This is called "price:');
            $table->string('gateway_paypal_plan_monthly', 150)->nullable();
            $table->string('gateway_paypal_plan_yearly', 150)->nullable();
            $table->string('gateway_razorpay_plan_monthly', 150)->nullable();
            $table->string('gateway_razorpay_plan_yearly', 150)->nullable();
            $table->string('gateway_paystack_plan_monthly', 150)->nullable();
            $table->string('gateway_paystack_plan_yearly', 150)->nullable();
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('featured', 10)->default('no')->comment('yes|no');
            $table->integer('limits_clients')->default('0')->comment('-1 is unlimited');
            $table->integer('limits_team')->default('0')->comment('-1 is unlimited');
            $table->integer('limits_projects')->default('0')->comment('-1 is unlimited');
            $table->string('module_projects', 10)->default('yes')->comment('yes|no');
            $table->string('module_tasks', 10)->default('yes')->comment('yes|no');
            $table->string('module_invoices', 10)->default('yes')->comment('yes|no');
            $table->string('module_leads', 10)->default('yes')->comment('yes|no');
            $table->string('module_knowledgebase', 10)->default('yes')->comment('yes|no');
            $table->string('module_estimates', 10)->default('yes')->comment('yes|no');
            $table->string('module_expense', 10)->default('yes')->comment('yes|no');
            $table->string('module_subscriptions', 10)->default('yes')->comment('yes|no');
            $table->string('module_tickets', 10)->default('yes')->comment('yes|no');
            $table->string('module_timetracking', 10)->default('yes')->comment('yes|no');
            $table->string('module_reminders', 10)->default('yes')->comment('yes|no');
            $table->string('module_proposals', 10)->default('yes')->comment('yes|no');
            $table->string('module_contracts', 10)->default('yes')->comment('yes|no');
            $table->string('module_messages', 10)->default('yes')->comment('yes|no');
            $table->string('status', 10)->default('active')->comment('active|archived');
            $table->string('visibility', 20)->default('visible')->comment('visible|hidden');
            $table->datetime('sync_date')->nullable();
            $table->string('sync_status', 40)->default('synced')->comment('awaiting-sync|syncing|synced');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
