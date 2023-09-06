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
        Schema::create('parents', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('secondary_email')->nullable();
            $table->boolean('status')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('address2')->nullable();
            $table->string('phone_alternate')->nullable();
            $table->text('referral_source')->nullable();
            $table->bigInteger('added_by');
            $table->timestamp('added_on');
            $table->boolean('referral_from_positive_experience_with_tutor');
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
        Schema::drop('parents');
    }
};
