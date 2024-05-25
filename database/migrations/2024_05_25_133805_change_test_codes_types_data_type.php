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
        Schema::table('mock_test_codes', function (Blueprint $table) {
            $table->unsignedInteger('test_type')->default(6)->comment('list data id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mock_test_codes', function (Blueprint $table) {
            $table->enum('test_type', ['SAT', 'ACT'])->change();
        });
    }
};
