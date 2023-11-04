<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('non_invoice_packages', function (Blueprint $table) {
            $table->boolean('allow_partial_payment')->default(false)->after('final_amount');
        });
    }

    public function down(): void
    {
        Schema::table('non_invoice_packages', function (Blueprint $table) {
            $table->dropColumn('allow_partial_payment');
        });
    }
};
