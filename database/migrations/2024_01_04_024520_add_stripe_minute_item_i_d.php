<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monthly_invoice_subscriptions', function (Blueprint $table) {
            $table->string('stripe_minutes_item_id')->nullable()->after('stripe_item_id');
        });
    }

    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            $table->dropColumn('stripe_minutes_item_id');
        });
    }
};
