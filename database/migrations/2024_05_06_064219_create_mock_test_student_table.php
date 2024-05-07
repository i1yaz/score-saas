<?php

use App\Models\MockTest;
use App\Models\MockTestCode;
use App\Models\Student;
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
        Schema::create('mock_test_student', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MockTest::class)->constrained();
            $table->foreignIdFor(Student::class)->constrained();
            $table->foreignIdFor(MockTestCode::class)->constrained();
            $table->string('signup_status')->nullable();
            $table->boolean('extra_time')->default(false);
            $table->string('score')->nullable();
            $table->enum('score_report_type',['FILE','URL'])->default('FILE');
            $table->string('score_report_path')->nullable();
            $table->json('subsection_scores')->nullable();
            $table->tinyText('notes_to_proctor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mock_test_student');
    }
};
