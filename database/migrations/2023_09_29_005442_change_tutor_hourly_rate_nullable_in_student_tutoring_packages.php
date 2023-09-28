<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('student_tutoring_packages', function (Blueprint $table) {
            $table->decimal('tutor_hourly_rate', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('student_tutoring_packages', function (Blueprint $table) {
            $table->decimal('tutor_hourly_rate', 8, 2)->nullable(false)->change();
        });
    }
};
