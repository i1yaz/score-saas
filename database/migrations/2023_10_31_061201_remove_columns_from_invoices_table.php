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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('paid_by_modal');
            $table->dropColumn('paid_by_id');
            $table->dropColumn('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('paid_by_modal')->nullable();
            $table->unsignedBigInteger('paid_by_id')->nullable();
            $table->double('amount_paid')->default(0);
        });
    }
};
