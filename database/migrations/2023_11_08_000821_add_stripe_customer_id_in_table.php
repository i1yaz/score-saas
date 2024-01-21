<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('remember_token');
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('remember_token');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('remember_token');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_customer_id']);
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn(['stripe_customer_id']);
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['stripe_customer_id']);
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['stripe_customer_id']);
        });
    }
};
