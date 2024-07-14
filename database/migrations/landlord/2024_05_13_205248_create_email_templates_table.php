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
        Schema::connection('landlord')->create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('lang')->nullable()->comment('to match to language');
            $table->string('type')->nullable()->index()->comment('everyone|admin|client');
            $table->string('category')->nullable()->index()->comment('users|projects|tasks|leads|tickets|billing|estimates|other|system');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->text('variables')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->string('status')->default('enabled')->comment('enabled|disabled');
            $table->string('language')->nullable();
            $table->string('real_template')->default('yes')->comment('yes|no');
            $table->string('show_enabled')->default('yes')->comment('yes|no');
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
        Schema::connection('landlord')->dropIfExists('email_templates');
    }
};
