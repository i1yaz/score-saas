<?php

use App\Models\StudentTutoringPackage;
use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_tutoring_package_subject', function (Blueprint $table) {
            $table->foreignIdFor(Subject::class)->constrained(indexName: 'tutoring_package_subject');
            $table->foreignIdFor(StudentTutoringPackage::class)->constrained(indexName: 'tutoring_package_id_subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_tutoring_package_subject');
    }
};
