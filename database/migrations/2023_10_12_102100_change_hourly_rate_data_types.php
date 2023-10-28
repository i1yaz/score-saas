<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('amount_paid')->default(0)->change();
        });

        Schema::table('student_tutoring_packages', function (Blueprint $table) {
            $table->decimal('hours')->change();
            $table->decimal('hourly_rate')->change();
            $table->decimal('discount')->nullable()->change();
            $table->decimal('tutor_hourly_rate')->default(0)->change();
            $table->unsignedSmallInteger('discount_type')->nullable()->change();
        });

        Schema::table('tutors', function (Blueprint $table) {
            $table->decimal('hourly_rate')->after('password')->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('amount_paid')->default(0)->change();
        });
        Schema::table('student_tutoring_packages', function (Blueprint $table) {
            $table->integer('hours')->default(0)->change();
            $table->integer('hourly_rate')->default(0)->change();
            $table->integer('discount')->default(0)->change();
            $table->integer('discount_type')->change();
            $table->integer('tutor_hourly_rate')->default(0)->change();
        });

        Schema::table('tutors', function (Blueprint $table) {
            $table->string('hourly_rate')->nullable()->change();
        });
    }
};
