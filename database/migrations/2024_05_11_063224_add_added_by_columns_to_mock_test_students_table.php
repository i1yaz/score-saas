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
        Schema::table('mock_test_student', function (Blueprint $table) {
            $table->unsignedBigInteger('added_by')->nullable()->after('notes_to_proctor');
            $table->string('auth_guard')->nullable()->after('notes_to_proctor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mock_test_student', function (Blueprint $table) {
            $table->dropColumn('added_by');
            $table->dropColumn('auth_guard');
        });
    }
};
