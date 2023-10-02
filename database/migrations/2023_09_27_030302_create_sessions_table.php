<?php

use App\Models\StudentTutoringPackage;
use App\Models\TutoringLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(StudentTutoringPackage::class)->constrained();
            $table->foreignIdFor(TutoringLocation::class)->constrained();
            $table->dateTime('scheduled_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('pre_session_notes')->nullable();
            $table->unsignedSmallInteger('session_completion_code')->nullable();
            $table->integer('how_was_session')->nullable();
            $table->string('student_parent_session_notes')->nullable();
            $table->string('homework')->nullable();
            $table->string('internal_notes')->nullable();
            $table->boolean('flag_session')->nullable();
            $table->boolean('home_work_completed')->nullable();
            $table->boolean('status')->default(true);
            $table->string('auth_guard');
            $table->bigInteger('added_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
