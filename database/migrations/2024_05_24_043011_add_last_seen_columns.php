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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('email');
            $table->string('last_ip_address')->nullable()->after('email');
        });
        Schema::table('tutors', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('email');
            $table->string('last_ip_address')->nullable()->after('email');
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('email');
            $table->string('last_ip_address')->nullable()->after('email');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('email');
            $table->string('last_ip_address')->nullable()->after('email');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('email');
            $table->string('last_ip_address')->nullable()->after('email');
        });
//        Schema::table('proctors', function (Blueprint $table) {
//            $table->timestamp('last_seen_at')->nullable();
//            $table->string('last_ip_address')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_ip_address');
        });
        Schema::table('tutors', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
            $table->dropColumn('last_ip_address');
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
            $table->dropColumn('last_ip_address');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
            $table->dropColumn('last_ip_address');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
            $table->dropColumn('last_ip_address');
        });
//        Schema::table('proctors', function (Blueprint $table) {
//            $table->dropColumn('last_seen_at');
//            $table->dropColumn('last_ip_address');
//        });

    }
};
