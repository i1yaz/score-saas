<?php

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
        Schema::create('monthly_invoice_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIdFor(Student::class)->constrained();
            $table->foreignIdFor(TutoringLocation::class)->constrained();
            $table->string('notes');
            $table->string('internal_notes');
            $table->date('start_date');
            $table->decimal('hourly_rate');
            $table->decimal('tutor_hourly_rate');
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
        Schema::drop('monthly_invoice_packages');
    }
};
