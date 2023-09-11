<?php

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
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->boolean('email_known')->nullable();
            $table->boolean('testing_accommodation')->nullable();
            $table->string('testing_accommodation_nature')->nullable();
            $table->string('official_baseline_act_score')->nullable();
            $table->string('official_baseline_sat_score')->nullable();
            $table->boolean('test_anxiety_challenge')->nullable();
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
        Schema::drop('students');
    }
};
