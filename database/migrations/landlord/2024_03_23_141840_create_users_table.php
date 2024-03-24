<?php

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
        Schema::connection('landlord')->create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('creator_id')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('type')->default('admin')->comment('admin|staff');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('primary_admin')->default('no')->comment('yes | no (only 1 primary admin - created during setup)');
            $table->string('avatar_directory')->nullable();
            $table->string('avatar_filename')->nullable();
            $table->string('status')->default('active')->comment('active|suspended|deleted');
            $table->dateTime('last_seen')->nullable();
            $table->string('theme')->default('default');
            $table->string('forgot_password_token')->nullable()->comment('random token');
            $table->dateTime('forgot_password_token_expiry')->nullable();
            $table->string('pref_leftmenu_position')->default('collapsed')->comment('collapsed | open');
            $table->string('pref_email_notifications')->default('yes')->comment('yes | no');
            $table->string('welcome_email_sent')->default('no')->comment('yes|no');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_user');
    }
};
