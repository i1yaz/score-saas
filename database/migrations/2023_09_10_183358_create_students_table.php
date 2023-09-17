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
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('parent_id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('email_known')->nullable();
            $table->boolean('testing_accommodation')->nullable();
            $table->string('testing_accommodation_nature')->nullable();
            $table->string('official_baseline_act_score')->nullable();
            $table->string('official_baseline_sat_score')->nullable();
            $table->boolean('test_anxiety_challenge')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('added_by');
            $table->timestamp('added_at');
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
