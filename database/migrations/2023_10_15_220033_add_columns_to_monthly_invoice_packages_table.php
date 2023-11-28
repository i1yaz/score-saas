<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monthly_invoice_packages', function (Blueprint $table) {
            $table->decimal('discount')->default(0)->after('tutor_hourly_rate');
            $table->unsignedSmallInteger('discount_type')->nullable()->after('discount');
            $table->boolean('is_free')->default(false)->after('discount_type');
            $table->boolean('is_score_guaranteed')->default(0)->after('is_free');
        });
    }

    public function down(): void
    {
        Schema::table('monthly_invoice_packages', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('discount_type');
            $table->dropColumn('is_free');
            $table->dropColumn('is_score_guaranteed');
        });
    }
};
