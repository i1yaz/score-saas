<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->string('password')->after('email_verified_at');
            $table->rememberToken()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            $table->dropColumn('password');
            $table->dropColumn('remember_token');
        });
    }
};
