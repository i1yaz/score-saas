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
        Schema::table('mock_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('added_by')->nullable()->after('end_time');
            $table->string('auth_guard')->nullable()->after('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mock_tests', function (Blueprint $table) {
            $table->dropColumn('added_by');
            $table->dropColumn('auth_guard');
        });
    }
};
