<?php

use App\Models\PackageType;
use App\Models\Student;
use App\Models\TutoringLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_tutoring_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Student::class);
            $table->foreignIdFor(PackageType::class);
            $table->foreignIdFor(TutoringLocation::class);
            $table->text('notes')->nullable();
            $table->text('internal_noted')->nullable();
            $table->integer('hours');
            $table->integer('hourly_rate');
            $table->integer('discount')->nullable();
            $table->integer('discount_type')->nullable();
            $table->date('start_date')->nullable();
            $table->integer('tutor_hourly_rate');
            $table->boolean('status')->default(true);
            $table->string('auth_guard');
            $table->bigInteger('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('student_tutoring_packages');
    }
};
