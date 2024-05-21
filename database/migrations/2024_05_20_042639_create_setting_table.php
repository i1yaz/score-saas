<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('standalone')->comment('standalone|saas');
            $table->integer('saas_tenant_id')->nullable();
            $table->string('saas_status')->nullable()->comment('unsubscribed|free-trial|awaiting-payment|failed|active|cancelled');
            $table->integer('saas_package_id')->nullable();
            $table->string('saas_onetime_login_key')->nullable();
            $table->string('saas_onetime_login_destination')->nullable()->comment('home|payment');
            $table->integer('saas_package_limits_tutors')->nullable();
            $table->integer('saas_package_limits_students')->nullable();
            $table->integer('saas_package_limits_monthly_packages')->nullable();
            $table->integer('saas_package_limits_student_packages')->nullable();
            $table->text('saas_notification_unique_id')->nullable()->comment('(optional) unique identifier');
            $table->text('saas_notification_body')->nullable()->comment('html body of promotion etc');
            $table->text('saas_notification_read')->nullable()->comment('yes|no');
            $table->text('saas_notification_action')->nullable()->comment('none|external-link|internal-link');
            $table->text('saas_notification_action_url')->nullable();
            $table->string('saas_email_server_type')->default('local')->comment('local |smtp');
            $table->text('saas_email_forwarding_address')->nullable();
            $table->text('saas_email_local_address')->nullable();
            $table->text('company_name')->nullable();
            $table->text('company_address_line_1')->nullable();
            $table->text('company_state')->nullable();
            $table->text('company_city')->nullable();
            $table->text('company_zipcode')->nullable();
            $table->text('company_country')->nullable();
            $table->text('company_telephone')->nullable();
            $table->text('company_custom_field_1')->nullable();
            $table->text('company_custom_field_2')->nullable();
            $table->text('company_custom_field_3')->nullable();
            $table->text('company_custom_field_4')->nullable();
            $table->text('clients_registration')->nullable()->comment('enabled | disabled');
            $table->text('clients_shipping_address')->nullable()->comment('enabled | disabled');
            $table->string('clients_disable_email_delivery')->default('disabled')->comment('enabled | disabled');
            $table->string('clients_app_login')->default('enabled')->comment('enabled | disabled');
            $table->text('email_general_variables')->nullable()->comment('common variable displayed available in templates');
            $table->text('email_from_address')->nullable();
            $table->text('email_from_name')->nullable();
            $table->text('email_server_type')->nullable()->comment('smtp|sendmail');
            $table->text('email_smtp_host')->nullable();
            $table->text('email_smtp_port')->nullable();
            $table->text('email_smtp_username')->nullable();
            $table->text('email_smtp_password')->nullable();
            $table->text('email_smtp_encryption')->nullable()->comment('tls|ssl|starttls');
            $table->integer('files_max_size_mb')->default(300)->comment('maximum size in MB');
            $table->text('system_timezone')->nullable();
            $table->text('system_date_format')->nullable()->comment('d-m-Y | d/m/Y | m-d-Y | m/d/Y | Y-m-d | Y/m/d | Y-d-m | Y/d/m');
            $table->text('system_datepicker_format')->nullable()->comment('dd-mm-yyyy | mm-dd-yyyy');
            $table->tinyInteger('system_pagination_limits')->nullable();
            $table->text('system_currency_code')->nullable();
            $table->text('system_currency_symbol')->nullable();
            $table->text('system_currency_position')->nullable()->comment('left|right');
            $table->text('system_decimal_separator')->nullable();
            $table->text('system_thousand_separator')->nullable();
            $table->string('system_close_modals_body_click')->default('no')->comment('yes|no');
            $table->string('system_language_default')->default('en')->comment('english|french|etc');
            $table->string('system_language_allow_users_to_change')->default('yes')->comment('yes|no');
            $table->string('system_logo_large_name')->default('logo.jpg');
            $table->string('system_logo_small_name')->default('logo-small.jpg');
            $table->string('system_logo_versioning')->default('1')->comment('used to refresh logo when updated');
            $table->string('system_session_login_popup')->default('disabled')->comment('enabled|disabled');
            $table->date('system_javascript_versioning')->nullable();
            $table->string('system_exporting_strip_html')->default('yes')->comment('yes|no');
            $table->text('invoices_prefix')->nullable();
            $table->smallInteger('invoices_recurring_grace_period')->nullable()->comment('Number of days for due date on recurring invoices. If set to zero, invoices will be given due date same as invoice date');
            $table->text('invoices_default_terms_conditions')->nullable();
            $table->string('invoices_show_view_status')->nullable();

            $table->string('stripe_secret_key')->nullable();
            $table->string('stripe_public_key')->nullable();
            $table->text('stripe_webhooks_key')->nullable()->comment('from strip dashboard');
            $table->text('stripe_default_subscription_plan_id')->nullable();
            $table->text('stripe_currency')->nullable();
            $table->text('stripe_display_name')->nullable()->comment('what customer will see on payment screen');
            $table->text('stripe_status')->nullable()->comment('enabled|disabled');
            $table->string('subscriptions_prefix')->default('SUB-');
            $table->text('paypal_email')->nullable();
            $table->text('paypal_currency')->nullable();
            $table->text('paypal_display_name')->nullable()->comment('what customer will see on payment screen');
            $table->text('paypal_mode')->nullable()->comment('sandbox | live');
            $table->text('paypal_status')->nullable()->comment('enabled|disabled');
            $table->text('mollie_live_api_key')->nullable();
            $table->text('mollie_test_api_key')->nullable();
            $table->text('mollie_display_name')->nullable();
            $table->string('mollie_mode')->default('live');
            $table->text('mollie_currency')->nullable();
            $table->string('mollie_status')->default('disabled')->comment('enabled|disabled');
            $table->text('bank_details')->nullable();
            $table->text('bank_display_name')->nullable()->comment('what customer will see on payment screen');
            $table->text('bank_status')->nullable()->comment('enabled|disabled');
            $table->text('razorpay_key_id')->nullable();
            $table->text('razorpay_secret_key')->nullable();
            $table->text('razorpay_currency')->nullable();
            $table->text('razorpay_display_name')->nullable();
            $table->string('razorpay_status')->default('disabled');
            $table->string('completed_check_email')->default('no')->comment('yes|no');
            $table->string('expenses_billable_by_default')->default('yes')->comment('yes|no');
            $table->string('theme_name')->default('default')->comment('default|darktheme');
            $table->text('theme_head')->nullable();
            $table->text('theme_body')->nullable();
            $table->text('track_thank_you_session_id')->nullable()->comment('used to ensure we show thank you page just once');
            $table->string('proposals_prefix')->default('PROP-');
            $table->string('proposals_show_view_status')->default('yes')->comment('yes|no');
            $table->string('contracts_prefix')->default('CONT-');
            $table->string('contracts_show_view_status')->default('yes')->comment('yes|no');
            $table->string('cronjob_has_run')->default('no')->comment('yes|no');
            $table->dateTime('cronjob_last_run')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
