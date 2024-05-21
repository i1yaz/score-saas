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
        Schema::connection('landlord')->table('packages', function (Blueprint $table) {
            $table->integer('max_students')->default('0')->comment('-1 is unlimited')->after('is_featured');
            $table->integer('max_student_packages')->default('0')->comment('-1 is unlimited')->after('is_featured');
            $table->integer('max_monthly_packages')->default('0')->comment('-1 is unlimited')->after('is_featured');
            $table->integer('max_tutors')->default('0')->comment('-1 is unlimited')->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('packages', function (Blueprint $table) {
            $table->dropColumn('max_students');
            $table->dropColumn('max_student_packages');
            $table->dropColumn('max_monthly_packages');
            $table->dropColumn('max_tutors');
        });
    }
};
