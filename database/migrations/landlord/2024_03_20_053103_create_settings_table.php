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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('version', 10)->nullable();
            $table->string('frontend_domain', 200)->nullable();
            $table->string('base_domain', 200)->nullable();
            $table->string('email_domain', 200)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('company_address_line_1', 100)->nullable();
            $table->string('company_state', 100)->nullable();
            $table->string('company_city', 100)->nullable();
            $table->string('company_zipcode', 100)->nullable();
            $table->string('company_country', 100)->nullable();
            $table->string('company_telephone', 100)->nullable();
            $table->text('code_meta_title')->nullable();
            $table->text('code_meta_description')->nullable();
            $table->text('code_head')->nullable();
            $table->text('code_body')->nullable();
            $table->text('email_general_variables')->nullable();
            $table->string('email_from_address', 100)->nullable();
            $table->string('email_from_name', 100)->nullable();
            $table->string('email_server_type', 100)->nullable();
            $table->string('email_smtp_host', 100)->nullable();
            $table->string('email_smtp_port', 100)->nullable();
            $table->string('email_smtp_username', 100)->nullable();
            $table->string('email_smtp_password', 100)->nullable();
            $table->string('email_smtp_encryption', 100)->nullable();
            $table->string('favicon_landlord_filename', 100)->nullable();
            $table->string('favicon_frontend_filename', 100)->nullable();
            $table->string('free_trial', 10)->default('no')->comment('yes|no');
            $table->integer('free_trial_days')->default(0)->comment('optional');
            $table->string('frontend_status', 100)->default('enabled')->comment('enabled|disabed');
            $table->string('purchase_code', 100)->nullable();
            $table->text('reserved_words')->nullable();
            $table->string('system_timezone', 100)->nullable();
            $table->string('system_date_format', 100)->nullable();
            $table->string('system_datepicker_format', 100)->nullable();
            $table->string('system_default_leftmenu', 100)->nullable();
            $table->string('system_pagination_limits', 100)->nullable();
            $table->string('system_currency_code', 100)->nullable();
            $table->string('system_currency_symbol', 100)->nullable();
            $table->string('system_currency_position', 100)->nullable();
            $table->string('system_decimal_separator', 100)->nullable();
            $table->string('system_thousand_separator', 100)->nullable();
            $table->string('system_language_default', 100)->nullable();
            $table->string('system_renewal_grace_period', 100)->default('3')->comment('days');
            $table->string('system_logo_large_name', 100)->nullable();
            $table->string('system_logo_small_name', 100)->nullable();
            $table->string('system_logo_frontend_name', 100)->nullable();
            $table->string('offline_payments_status', 100)->default('disabled')->comment('enabled|disabled');
            $table->string('offline_payments_display_name', 100)->nullable();
            $table->text('offline_payments_details')->nullable();
            $table->text('offline_proof_of_payment_message')->nullable();
            $table->text('offline_proof_of_payment_thank_you')->nullable();
            $table->string('onboarding_status', 50)->default('disabled')->comment('enabled|disabled');
            $table->text('onboarding_content')->nullable();
            $table->string('stripe_secret_key', 150)->nullable();
            $table->string('stripe_public_key', 150)->nullable();
            $table->string('stripe_webhooks_key', 150)->nullable();
            $table->string('stripe_default_subscription_plan_id', 150)->nullable();
            $table->string('stripe_display_name', 150)->nullable();
            $table->string('stripe_status', 30)->default('disabled')->comment('enabled|disabled');
            $table->string('paypal_display_name', 150)->nullable();
            $table->string('paypal_sandbox_client_id', 250)->nullable();
            $table->string('paypal_sandbox_secret_key', 250)->nullable();
            $table->string('paypal_live_client_id', 250)->nullable();
            $table->string('paypal_live_secret_key', 250)->nullable();
            $table->string('paypal_subscription_product_id', 250)->nullable()->comment('the product that wilb used to contain all plans');
            $table->string('paypal_mode', 30)->default('live')->comment('sandbox | live');
            $table->string('paypal_status', 30)->default('disabled')->comment('enabled|disabled');
            $table->string('paystack_secret_key', 250)->nullable();
            $table->string('paystack_public_key', 250)->nullable();
            $table->string('paystack_display_name', 250)->nullable();
            $table->string('paystack_status', 50)->default('disabled')->comment('enabled|disabled');
            $table->string('razorpay_api_key', 250)->nullable();
            $table->string('razorpay_display_name', 250)->nullable();
            $table->string('razorpay_webhooks_secret', 250)->nullable();
            $table->string('razorpay_status', 50)->default('disabled')->comment('enabled|disabled');
            $table->string('razorpay_secret_key', 250)->nullable();
            $table->string('gateways_default_product_name', 150)->nullable()->comment('used to create a default product in some payment gateways (like paypal)');
            $table->string('gateways_default_product_description', 250)->nullable()->comment('used to create a default product in some payment gateways (like paypal)');
            $table->string('files_max_size_mb', 100)->nullable();
            $table->string('system_javascript_versioning', 100)->nullable();
            $table->text('terms_of_service')->nullable();
            $table->string('terms_of_service_text', 250)->nullable();
            $table->string('terms_of_service_status', 30)->default('disabled')->comment('enabled|disabled');
            $table->string('theme_name', 100)->nullable();
            $table->string('cronjob_has_run', 100)->default('no')->comment('yes|no');
            $table->dateTime('cronjob_last_run')->nullable();
            $table->string('cronjob_has_run_tenants', 10)->default('no')->comment('yes|no');
            $table->dateTime('cronjob_last_run_tenants')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
