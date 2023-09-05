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
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('status');
            $table->string('phone');
            $table->text('address');
            $table->text('address2');
            $table->string('phone_alternate');
            $table->text('referral_source');
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
