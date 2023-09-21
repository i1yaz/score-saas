<?php

use App\Models\StudentTutoringPackage;
use App\Models\Tutor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_tutoring_package_tutor', function (Blueprint $table) {
            $table->foreignIdFor(Tutor::class)->constrained(indexName: 'tutoring_package_tutor');
            $table->foreignIdFor(StudentTutoringPackage::class)->constrained(indexName: 'tutoring_package_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_tutoring_package_tutor');
    }
};
