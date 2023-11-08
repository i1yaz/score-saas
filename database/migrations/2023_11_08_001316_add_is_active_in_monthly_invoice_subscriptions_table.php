<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monthly_invoice_subscriptions', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('monthly_invoice_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['is_active']);
        });
    }
};
