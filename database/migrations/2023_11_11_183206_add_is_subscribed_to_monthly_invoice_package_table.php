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
        Schema::table('monthly_invoice_packages', function (Blueprint $table) {
            $table->boolean('is_subscribed')->default(\App\Models\MonthlyInvoicePackage::SUBSCRIPTION_INACTIVE)->after('is_score_guaranteed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_invoice_packages', function (Blueprint $table) {
            $table->dropColumn('is_subscribed');
        });
    }
};
